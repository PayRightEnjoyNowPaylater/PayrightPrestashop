<?php
/**
 * Placeholder for Cancel Response (Deprecated)
 *
 * @author Payright
 * @copyright 2016-2019 https://www.payright.com.au
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

require_once(_PS_MODULE_DIR_ . 'payright/classes/PayrightCapture.php');
require_once(_PS_MODULE_DIR_.'payright/PayrightSDK/api/Call.php');
require_once(_PS_MODULE_DIR_.'payright/PayrightSDK/api/Response.php');
require_once(_PS_MODULE_DIR_.'payright/PayrightSDK/api/PayRightConfig.php');

use Payright\api\Call;
use Payright\api\Response;
use PrestaShop\PrestaShop\Core\Payment\PaymentOption;
use Payright\api\PayRightConfig;

ini_set("display_errors", "1");
class PayrightCancelModuleFrontController extends ModuleFrontController
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
        $this->checkoutId = $_REQUEST['checkoutId'];

        $this->context->smarty->assign(array(
            "checkoutId" => $this->checkoutId,
        ));

        $checkoutId = $_REQUEST['checkoutId'];

        $this->retrievePayrightConfiguration();
        $ConfigValues = $this->getConfigFormValues();

       

        $PayRightConfig = new Payright\api\PayRightConfig($ConfigValues, null);
        $PayRightApiCall = new Payright\api\Call($PayRightConfig);

        if (isset($checkoutId)) {

            $status = $PayRightApiCall->planStatusChange($PayRightConfig, $checkoutId);

            if (array_key_exists('error', $status['data'])) {
                $planResult = $status['data']['error_message'];
            } else {
                $planResult = $status['data']['message'];
            }

            PayrightOrder::updatePaymentStatus($planResult, $checkoutId);
        }
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
        $this->payrightLiveMode     =   Configuration::get('PAYRIGHT_LIVE_MODE');
        $this->payrightRegion     =   Configuration::get('PAYRIGHT_REGION');
        $this->payrightApiKey  = Configuration::get('PS_PAYRIGHT_APIKEY');

        $this->payrightProductInstallments = Configuration::get('CATEGORYPAGE_PAYRIGHTINSTALLMENTS');
        $this->payrightinfoModal = Configuration::get('INFOMODAL_TEMPLATE');
    }


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
