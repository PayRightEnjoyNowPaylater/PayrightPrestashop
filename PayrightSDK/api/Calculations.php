<?php
/**
 * Payright Calculationss
 *
 * @author Payright
 * @copyright 2016-2019 https://www.payright.com.au
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Payright\api;

class Calculations
{
    public function __construct()
    {
        # code...
    }

    /**
     * Calculate the product installment
     * @param int $saleAmount amount of purchased product
     * @return calculated installment of sale amount
     */
    public function calculateSingleProductInstallment($getPayrightConfigurationValue, $saleAmount)
    {
        $unserializeRatesArray = $getPayrightConfigurationValue['rates'];
        
        if ($unserializeRatesArray && $saleAmount > 0) {
            $accountKeepingFees = $getPayrightConfigurationValue['account_keeping_fee'];
            $paymentProcessingFee = $getPayrightConfigurationValue['payment_processing_fee'];
     
            $LoanTerm = $this->fetchLoanTermForSale($unserializeRatesArray, $saleAmount);

            $getMinDeposit = $this->calculateMinDeposit($unserializeRatesArray, $saleAmount, $LoanTerm);

            $getFrequancy = $this->getPaymentFrequency($accountKeepingFees, $LoanTerm);
            $calculatedNoofRepayments = $getFrequancy['numberofRepayments'];
            $calculatedAccountKeepingFees = $getFrequancy['accountKeepingFees'];

            if ($calculatedNoofRepayments == 0){
                return false;
            }

            $LoanAmount = $saleAmount - $getMinDeposit;


            $formatedLoanAmount = number_format((float)$LoanAmount, 2, '.', '');

            $resEstablishmentFees = $this->getEstablishmentFees(
                $LoanTerm,
                $getPayrightConfigurationValue['establishment_fee']
            );

            $CalculateRepayments  = $this->calculateRepayment(
                $calculatedNoofRepayments,
                $calculatedAccountKeepingFees,
                $resEstablishmentFees,
                $LoanAmount,
                $paymentProcessingFee
            );
            $dataResponseArray = array();
            $dataResponseArray['LoanAmount'] = $LoanAmount;
            $dataResponseArray['EstablishmentFee'] = $resEstablishmentFees;
            $dataResponseArray['minDeposit'] = $getMinDeposit;
            $dataResponseArray['TotalCreditRequired'] = $this->totalCreditRequired(
                $formatedLoanAmount,
                $resEstablishmentFees
            );
            $dataResponseArray['Accountkeepfees'] = $accountKeepingFees;
            $dataResponseArray['processingfees'] = $paymentProcessingFee;
            $dataResponseArray['saleAmount'] =  $saleAmount;
            $dataResponseArray['noofrepayments'] = $calculatedNoofRepayments;
            $dataResponseArray['repaymentfrequency'] = 'Fortnightly';
            $dataResponseArray['LoanAmountPerPayment'] =  $CalculateRepayments;
  
            return $dataResponseArray;
        } else {
            return "exceed_amount";
        }
    }

    public function fetchLoanTermForSale($rates, $saleAmount)
    {
        $rates_array = array();

        foreach ($rates as $key => $rate) {
            $rates_array[$key]['term'] = $rate->term;
            $rates_array[$key]['minimumPurchase']  = $rate->minimumPurchase;
            $rates_array[$key]['maximumPurchase']  = $rate->maximumPurchase;

            if (($saleAmount >= $rates_array[$key]['minimumPurchase'] && $saleAmount <= $rates_array[$key]['maximumPurchase'])) {
                
                $generate_loan_term[] = $rates_array[$key]['term'];
            }
        }

        if (isset($generate_loan_term)) {
            return min($generate_loan_term);
        } else {
            return 0;
        }
    }


    /**
     * Calculate Minimum deposit trhat needs to be pay for sale amount
     * @param array $getRates
     * @param int $saleAmount amount for purchased product
     * @return float mindeposit
     */


    public function calculateMinDeposit($getRates, $saleAmount, $loanTerm)
    {
        // Iterate through each term, apply the minimum deposit to the sale amount and see if it fits in the rate card. If not found, move to a higher term
        foreach ($getRates as $rate) {
            $minimumDepositPercentage = $rate->minimumDepositPercentage;
            $depositAmount = $saleAmount * ($minimumDepositPercentage / 100);
            $loanAmount = $saleAmount - $depositAmount;

            // Check if loan amount is within range
            if ($loanAmount >= $rate->minimumPurchase && $loanAmount <= $rate->maximumPurchase) {
                return sprintf('%01.2f', $depositAmount);
            }
        }
        // No valid term and deposit found
        return 0;
    }


    /**
    * Payment frequancy for loan amount
    * @param float $accountKeepingFees account keeping fees
    * @param int $LoanTerm loan term
    * @param array $returnArray noofpayments and accountkeeping fees
    */

    public function getPaymentFrequency($accountKeepingFees, $LoanTerm)
    {
        $RepaymentFrequecy = 'Fortnightly';
        
        if ($RepaymentFrequecy == 'Weekly') {
            $j = floor($LoanTerm * (52/12));
            $o = $accountKeepingFees * 12 / 52;
        }

        if ($RepaymentFrequecy == 'Fortnightly') {
            $j = floor($LoanTerm*(26/12));
            if ($LoanTerm == 3) {
                $j = 7;
            }
            $o = $accountKeepingFees * 12 / 26;
        }

        

        $numberofRepayments = $j;
        $accountKeepingFees = $o;
        $returnArray = array();
        $returnArray['numberofRepayments'] = $numberofRepayments;
        $returnArray['accountKeepingFees'] = round($accountKeepingFees, 2);

        return $returnArray;
    }


    /**
     * Get the establishment fees
     * @param int $loanTerm loan term for sale amount
     * @return  calculated establishment fees
     */

    public function getEstablishmentFees($LoanTerm, $establishmentFees)
    {
        $fee_bandArray = array();
        $feebandCalculator = 0;
        $h = 0;
       
        foreach ($establishmentFees as $key => $row) {
            $fee_bandArray[$key]['term'] = $row->term;
            $fee_bandArray[$key]['initial_est_fee'] = $row->initialEstFee;
            $fee_bandArray[$key]['repeat_est_fee'] = $row->repeatEstFee;

            if ($fee_bandArray[$key]['term'] == $LoanTerm) {
                $h = $row->initialEstFee;
            }

            $feebandCalculator++;
        }
        return $h;
    }


    /**
     * Calculate Repayment installment
     * @param int $numberOfRepayments term for sale amount
     * @param int $AccountKeepingFees account keeping fees
     * @param int $establishmentFees establishment fees
     * @param int $LoanAmount loan amount
     * @param int $paymentProcessingFee processing fees for loan amount
     *
     */

    public function calculateRepayment(
        $numberOfRepayments,
        $AccountKeepingFees,
        $establishmentFees,
        $LoanAmount,
        $paymentProcessingFee
    ) {
        $repayment_amount_init = ((floatval($establishmentFees) + floatval($LoanAmount)) / $numberOfRepayments);

        $repayment_amount = floatval($repayment_amount_init) + floatval($AccountKeepingFees) + floatval($paymentProcessingFee);

        return number_format((float)$repayment_amount, 2, '.', '');
    }


    /**
     * Get the total credit required
     * @param int $loanAmount lending amount
     * @param float $establishmentFees establishmentFees
     * @return float total credit allowed
     */

    public static function totalCreditRequired($LoanAmount, $establishmentFees)
    {
        $LoanAmount = (float)$LoanAmount;
        $establishmentFees = (float)$establishmentFees;

        $totalCreditRequired = ($LoanAmount + $establishmentFees) ;
        return number_format((float)$totalCreditRequired, 2, '.', '');
    }
}
