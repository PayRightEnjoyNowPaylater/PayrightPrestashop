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

        $ConfigValues = $this->getConfigFormValues();
        $PayRightConfig = new Payright\api\PayRightConfig($ConfigValues, null);
        $PayRightApiCall = new Payright\api\Call();
        
        $transactionData = array();
        $cart_id = $cart->id;
        $transactionData['transactionRef'] = $cart_id.rand();
        $transactionData['id_cart'] = $cart_id;
        $baseUrl = Tools::getHttpHost(true).__PS_BASE_URI__;
        $order_total = $cart->getOrderTotal();
        $redirect_Url = 'module/payright/return';

        $initialiseTransaction = $PayRightApiCall->initialiseTransaction(
            $order_total,
            $transactionData,
            $PayRightConfig,
            $baseUrl.$redirect_Url
        );
        
        $initialiseTransactionData = json_decode($initialiseTransaction);
        $redirectUrl = $initialiseTransactionData->data->redirectEndpoint;

        Tools::redirect($redirectUrl);
    }

      /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'PAYRIGHT_LIVE_MODE' => Configuration::get('PAYRIGHT_LIVE_MODE', true),
            'PAYRIGHT_REGION' => Configuration::get('PAYRIGHT_REGION', true),
            'PS_PAYRIGHT_APIKEY' => Configuration::get('PS_PAYRIGHT_APIKEY', null),
            'PRODUCTPAGE_PAYRIGHTINSTALLMENTS' => Configuration::get('PRODUCTPAGE_PAYRIGHTINSTALLMENTS', null),
            'CATEGORYPAGE_PAYRIGHTINSTALLMENTS' => Configuration::get('CATEGORYPAGE_PAYRIGHTINSTALLMENTS', null),
            'INFOMODAL_TEMPLATE' => Configuration::get('INFOMODAL_TEMPLATE', null)

        );
    }
}
