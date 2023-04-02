<?php
/**
 * Placeholder Return the response
 *
 * @author Payright
 * @copyright 2016-2019 https://www.payright.com.au
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

require_once _PS_MODULE_DIR_ . 'payright/classes/PayrightCapture.php';
require_once _PS_MODULE_DIR_ . 'payright/PayrightSDK/api/Call.php';
require_once _PS_MODULE_DIR_ . 'payright/PayrightSDK/api/Response.php';
require_once _PS_MODULE_DIR_ . 'payright/PayrightSDK/api/PayRightConfig.php';

//use Payright\api\Call;
use Payright\api\PayRightConfig;
use Payright\api\Response;

class PayrightReturnModuleFrontController extends ModuleFrontController
{
    private $params;

    private $payright_merchant_id;
    private $payright_merchant_key;
    private $payright_api_environment;
    private $payright_user_agent;

    private $payrightLiveMode;
    private $payrightAccountEmail;
    private $payrightAccountPassword;
    private $payrightApiKey;
    private $payrightUserName;
    private $payrightClientID;
    private $payrightProductInstallments;
    private $payrightCategoryInstallments;
    private $payrightinfoModal;
    private $payrightMerchantUsername;
    private $payrightMerchantPassword;
    private $planId;

    private function validateCredentials($params)
    {
        $error = array();

        if (empty($params["ecommtoken"])) {
            $error[] = "No Payright Ecommerce Token Found !";
        }
        return $error;
    }

    /**
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {
        $this->plan_status = $_REQUEST['status'];
        $this->checkout_id = $_REQUEST['checkoutId'];

        $this->context->smarty->assign(array(
            "plan_status" => $this->plan_status,
            "checkout_id" => $this->checkout_id,
        ));

        $plan_status         = $_REQUEST['status'];
        $checkout_id         = $_REQUEST['checkoutId'];

        $this->retrievePayrightConfiguration();
        $ConfigValues = $this->getConfigFormValues();

        $PayRightConfig  = new Payright\api\PayRightConfig($ConfigValues, null);
        $PayRightApiCall = new Payright\api\Call($PayRightConfig);

        $planData = $PayRightApiCall->getPlanDataById($PayRightConfig, $checkout_id);
        $planObj  = json_decode($planData);

        
        $transactionObj = $planObj->data;

        $planId = $transactionObj->planId;

        switch ($plan_status) {
            case Payright\api\Response::RESPONSE_STATUS_COMPLETE:
                //// this is the response status
                $this->doCapture($planId, $checkout_id);
                break;
            case Payright\api\Response::RESPONSE_STATUS_DECLINE:
                $error["error"]   = true;
                $error["message"] = "Payright Transaction Declined,
                please try again with an alternative payment provider";
                break;
            case Payright\api\Response::RESPONSE_STATUS_CANCELLED:
                $error["error"]   = true;
                $error["message"] = "Payright Transaction Failed, please contact Payright 1300 338 496";
                break;

            case Payright\api\Response::RESPONSE_STATUS_REVIEW:
                $error["error"]   = true;
                $error["message"] = "Payright Transaction Failed, please contact Payright 1300 338 496";
                break;

            case Payright\api\Response::RESPONSE_APPROVED_PENDINGID:
                $error["error"]   = true;
                $error["message"] = "Payright Transaction Failed, please contact Payright 1300 338 496";
                break;

            case Payright\api\Response::RESPONSE_STATUS_CANCEL:
                $error["error"]   = true;
                $error["message"] = "Payment has been cancelled by the customer.";
                break;
        }

        if (!empty($error["error"]) && $error["error"]) {
            ### redirect to the error page
            $this->checkoutErrorRedirect($error);
        } else {
            $customer = new Customer($this->context->cart->id_customer);
            Tools::redirect('index.php?controller=order-confirmation&id_cart=' . $this->context->cart->id . '&id_module=' . $this->module->id . '&id_order=' . $this->module->currentOrder . '&key=' . $customer->secure_key);
        }

        $this->setTemplate("module:payright/views/templates/front/payment_return.tpl");
    }

    private function doCapture($planId, $checkoutId)
    {
        $payright_capture = new PayrightCapture();
        $payright_capture->createCapturePayment($planId, $checkoutId);
    }

    private function checkoutErrorRedirect($results)
    {
        if (!empty($results["message"])) {
            $this->errors[] = $this->l($results["message"]);
        }
        $this->redirectWithNotifications('index.php?controller=order&step=1');
    }

    private function retrievePayrightConfiguration()
    {
        ###
        $this->payrightLiveMode     = Configuration::get('PAYRIGHT_LIVE_MODE');
        $this->payrightRegion     =   Configuration::get('PAYRIGHT_REGION');
        $this->payrightApiKey          = Configuration::get('PS_PAYRIGHT_APIKEY');

        $this->payrightProductInstallments = Configuration::get('CATEGORYPAGE_PAYRIGHTINSTALLMENTS');
        $this->payrightinfoModal           = Configuration::get('INFOMODAL_TEMPLATE');
    }

    protected function getConfigFormValues()
    {
        return array(
            'PAYRIGHT_LIVE_MODE'           => Configuration::get('PAYRIGHT_LIVE_MODE', true),
            'PAYRIGHT_REGION' => Configuration::get('PAYRIGHT_REGION', true),
            'PS_PAYRIGHT_APIKEY'           => Configuration::get('PS_PAYRIGHT_APIKEY', null),
            'PRODUCTPAGE_PAYRIGHTINSTALLMENTS'  => Configuration::get('PRODUCTPAGE_PAYRIGHTINSTALLMENTS', null),
            'CATEGORYPAGE_PAYRIGHTINSTALLMENTS' => Configuration::get('CATEGORYPAGE_PAYRIGHTINSTALLMENTS', null),
            'INFOMODAL_TEMPLATE'           => Configuration::get('INFOMODAL_TEMPLATE', null),
        );
    }
}
