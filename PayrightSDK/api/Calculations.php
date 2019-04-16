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
    public function calculateSingleProductInstallment($rates, $saleAmount, $cookieObj)
    {
        $unserializeRatesArray = unserialize($rates);
        $payrightInstallmentApproval = $this->getMaximumSaleAmount($unserializeRatesArray, $saleAmount);

        // echo "<pre>";
        //     print_r($payrightInstallmentApproval);
        // echo "</pre>";
        
        if ($payrightInstallmentApproval == 0) {
            $accountKeepingFees = $cookieObj->AccountKeepingfees;
            $paymentProcessingFee = $cookieObj->PaymentProcessingFee;
     
            $LoanTerm = $this->fetchLoanTermForSale($unserializeRatesArray, $saleAmount);

            $getMinDeposit = $this->calculateMinDeposit($unserializeRatesArray, $saleAmount, $LoanTerm);

            $getFrequancy = $this->getPaymentFrequency($accountKeepingFees, $LoanTerm);
            $calculatedNoofRepayments = $getFrequancy['numberofRepayments'];
            $calculatedAccountKeepingFees = $getFrequancy['accountKeepingFees'];


            $LoanAmount = $saleAmount - $getMinDeposit;


            $formatedLoanAmount = number_format((float)$LoanAmount, 2, '.', '');

            $resEstablishmentFees = $this->getEstablishmentFees($LoanTerm, $cookieObj->establishmentFeeArray);

            $CalculateRepayments  = $this->calculateRepayment(
                $calculatedNoofRepayments,
                $calculatedAccountKeepingFees,
                $resEstablishmentFees,
                $LoanAmount,
                $paymentProcessingFee
            );

            // var_dump($CalculateRepayments);
            $dataResponseArray = array();
            $dataResponseArray['LoanAmount'] = $LoanAmount;
            $dataResponseArray['EstablishmentFee'] = $resEstablishmentFees;
            $dataResponseArray['minDeposit'] = $getMinDeposit;
            $dataResponseArray['TotalCreditRequired'] = $this->totalCreditRequired(
                $formatedLoanAmount,
                $resEstablishmentFees
            );
            $dataResponseArray['Accountkeepfees'] = $cookieObj->AccountKeepingfees;
            $dataResponseArray['processingfees'] = $cookieObj->PaymentProcessingFee;
            $dataResponseArray['saleAmount'] =  $saleAmount;
            $dataResponseArray['noofrepayments'] = $calculatedNoofRepayments;
            $dataResponseArray['repaymentfrequency'] = 'Fortnightly';
            $dataResponseArray['LoanAmountPerPayment'] =  $CalculateRepayments;

            return $dataResponseArray;
        
        // } else {
        //     return "exceed_amount";
        // }
        } else {
            return "exceed_amount";
        }
    }

    /**
     * Get the maximum limit for sale amount
     * @param array $getRates get the rates for merchant
     * @param float $saleAmount price of purchased amount
     * @return int allowed loanlimit in form 0 or 1, 0 means sale amount is still in limit and 1 is over limit
     */

    public function getMaximumSaleAmount($getRates, $saleAmount)
    {
        $chkLoanlimit = 0;
       
        $keys = array_keys($getRates);
        $getVal = array();
        for ($i = 0; $i < count($getRates); $i++) {
            foreach ($getRates[$keys[$i]] as $key => $value) {
                if ($key == 4) {
                    $getVal[] = $value;
                }
            }
        }

        if (max($getVal) < $saleAmount) {
            $chkLoanlimit = 1;
        }

        return $chkLoanlimit;
    }


    public function fetchLoanTermForSale($rates, $saleAmount)
    {
        $ratesArray = array();
        $generateLoanTerm = array();
        foreach ($rates as $key => $rate) {
            $ratesArray[$key]['Term'] = $rate['2'];
            $ratesArray[$key]['Min'] = $rate['3'];
            $ratesArray[$key]['Max'] = $rate['4'];
        

            if (($saleAmount >= $ratesArray[$key]['Min'] && $saleAmount <= $ratesArray[$key]['Max'])) {
                $generateLoanTerm[] = $ratesArray[$key]['Term'];
            }
        }

        return min($generateLoanTerm);
    }


    /**
     * Calculate Minimum deposit trhat needs to be pay for sale amount
     * @param array $getRates
     * @param int $saleAmount amount for purchased product
     * @return float mindeposit
     */


    public function calculateMinDeposit($getRates, $saleAmount, $loanTerm)
    {
        $per = array();
        for ($i = 0; $i < count($getRates); $i++) {
            for ($l = 0; $l < count($getRates[$i]); $l++) {
                if ($getRates[$i][2] == $loanTerm) {
                    $per[] = $getRates[$i][1];
                }
            }
        }

        $percentage = min($per);
        $value = $percentage/100*$saleAmount;
        return money_format('%.2n', $value);
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

    public function getEstablishmentFees($LoanTerm, $establishmentFeeArray)
    {
        $establishmentFees = (array) unserialize($establishmentFeeArray);

        $fee_bandArray = array();
        $feebandCalculator = 0;
       
        foreach ($establishmentFees['records'] as $key => $row) {
            $fee_bandArray[$key]['term'] = $row->term;
            $fee_bandArray[$key]['initial_est_fee'] = $row->initial_est_fee;
            $fee_bandArray[$key]['repeat_est_fee'] = $row->repeat_est_fee;

            if ($fee_bandArray[$key]['term'] == $LoanTerm) {
                $h = $row->initial_est_fee;
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
        $establishmentFees = (float)$establishmentFees;
        $LoanAmount = (float)$LoanAmount;
        $AccountKeepingFees = (float)$AccountKeepingFees;
        $paymentProcessingFee = (float)$paymentProcessingFee;



        $RepaymentAmountInit = (($establishmentFees + $LoanAmount) / $numberOfRepayments);
        $RepaymentAmountInit = (float)$RepaymentAmountInit;
        $RepaymentAmount = $RepaymentAmountInit + $AccountKeepingFees;

        $RepaymentAmountCalc = $RepaymentAmount + $paymentProcessingFee;

        $RepaymentAmountCalc = $RepaymentAmountCalc / 1;
        return number_format((float)$RepaymentAmountCalc, 2, '.', '');
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
