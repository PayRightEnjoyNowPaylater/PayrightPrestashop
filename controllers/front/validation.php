<?php
/**
 * Placeholder for validation
 *
 * @author Payright
 * @copyright 2016-2019 https://www.payright.com.au
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

use Payright\api\Call;
use Payright\transaction;
use Payright\api\PayRightConfig;

class PayrightValidationModuleFrontController extends ModuleFrontController
{
    /**
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {
        $cart = $this->context->cart;
        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0
            || $cart->id_address_invoice == 0
            || !$this->module->active) {
            Tools::redirect('index.php?controller=order&step=1');
        }

        // Check that this payment option is still available in case the customer
        // changed his address just before the end of the checkout process
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == 'payright') {
                $authorized = true;
                break;
            }
        }

        if (!$authorized) {
            die($this->module->l('This payment method is not available.', 'validation'));
        }

        $this->context->smarty->assign(array(
           'params' => $_REQUEST,
        ));


         
        $payrightAccessToken = $_REQUEST['payrightauth'];

        $clientId = $_REQUEST['clientid'];



        $PayRightApiCall = new Payright\api\Call();
        $transactionData = array();
        $transactionData['transactionRef'] = $cart->id."_".$clientId.rand();
        $transactionData['clientId'] = $clientId;

        $intializeTransaction = $PayRightApiCall->intializeTransaction(
            $cart->getOrderTotal(),
            $payrightAccessToken,
            $transactionData
        );
        $intializeTransactionData = json_decode($intializeTransaction);
        $ecommToken = $intializeTransactionData->ecommToken;
        Tools::redirect('https://betadocsonlineapi.payright.com.au/loan/new/'.$ecommToken);
    }
}
