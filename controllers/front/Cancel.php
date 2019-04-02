<?php

require_once( _PS_MODULE_DIR_ . 'payright/classes/PayrightCapture.php' );
require_once( _PS_MODULE_DIR_.'payright/PayrightSDK/api/call.php');
require_once( _PS_MODULE_DIR_.'payright/PayrightSDK/api/Response.php');
require_once( _PS_MODULE_DIR_.'payright/PayrightSDK/api/PayRightConfig.php');

use Payright\api\Call;
use Payright\api\Response;
use PrestaShop\PrestaShop\Core\Payment\PaymentOption;
use Payright\api\PayRightConfig;

ini_set("display_errors", "1");
class payrightCancelModuleFrontController extends ModuleFrontController
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

    private function _validateCredentials($params) {
        $error = array();

        if(empty($params["ecommtoken"]) ) {
            $error[] = "No Payright Ecommerce Token Found !";
        }
        return $error;
    }

     /**
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {
       

        $this->params = $_REQUEST;

        $this->context->smarty->assign([
            "params" => $this->params,
        ]);

        $params = $_REQUEST;
        $validate_error = $this->_validateCredentials($params);

        if( count($validate_error) ) {
            $error["message"] = $this->module->l("Invalid Response: Missing Payright transaction " . implode($validate_error, ", ") , "validation");
            $this->_checkoutErrorRedirect($error);
        }

        $this->_retrievePayrightConfiguration();
        $ConfigValues = $this->getConfigFormValues();

       

        $PayRightConfig = new Payright\api\PayRightConfig(null,$ConfigValues);
        $PayRightApiCall = new Payright\api\Call($PayRightConfig);

        $ecommerceToken = $this->params['ecommtoken'];

        $GetTokenData = $PayRightApiCall->GetPlanDataByToken($ecommerceToken,$PayRightConfig);
        $TokenObject = json_decode($GetTokenData);

        $transactionObj = json_decode($TokenObject->transactionResult); 

        $error["error"]     =   true;
        $error["message"]   =   "Payright Transaction Failed, please contact Payright 1300 338 496";

        $PayRightApiCall->planStatusChange($ecommerceToken, $PayRightConfig, $transactionObj->planId);


       if(!empty($error["error"]) && $error["error"] ) {
                ### redirect to the error page 
              $this->_checkoutErrorRedirect($error);
        }
       
         $this->setTemplate("module:payright/views/templates/front/payment_return.tpl");
    }


    private function _checkoutErrorRedirect($results) {
        if( !empty($results["message"]) ) {
            $this->errors[] = $this->l( $results["message"] );
        }
        $this->redirectWithNotifications('index.php?controller=order&step=1');
    }

        private function _retrievePayrightConfiguration() {
        ### 
        $this->payrightLiveMode     =   Configuration::get('PAYRIGHT_LIVE_MODE');
        $this->payrightAccountEmail  = Configuration::get('PAYRIGHT_ACCOUNT_EMAIL');

        $this->payrightAccountPassword = Configuration::get('PAYRIGHT_ACCOUNT_PASSWORD');
        $this->payrightApiKey  = Configuration::get('PS_PAYRIGHT_APIKEY');

        $this->payrightUserName = Configuration::get('PS_PAYRIGHT_USERNAME');
        $this->payrightClientID = Configuration::get('PS_PAYRIGHT_CLIENTID');

        $this->payrightProductInstallments = Configuration::get('CATEGORYPAGE_PAYRIGHTINSTALLMENTS');
        $this->payrightinfoModal = Configuration::get('INFOMODAL_TEMPLATE');

        $this->payrightMerchantUsername = Configuration::get('PAYRIGHT_MERCHANTUSERNAME');
        $this->payrightMerchantPassword = Configuration::get('PAYRIGHT_MERCHANTPASSWORD'); 

    }


    protected function getConfigFormValues()
    {
        return array(
            'PAYRIGHT_LIVE_MODE' => Configuration::get('PAYRIGHT_LIVE_MODE', true),
            'PAYRIGHT_ACCOUNT_EMAIL' => Configuration::get('PAYRIGHT_ACCOUNT_EMAIL', 'contact@prestashop.com'),
            'PAYRIGHT_ACCOUNT_PASSWORD' => Configuration::get('PAYRIGHT_ACCOUNT_PASSWORD', null),
            'PS_PAYRIGHT_APIKEY' => Configuration::get('PS_PAYRIGHT_APIKEY', null),
            'PS_PAYRIGHT_USERNAME'=> Configuration::get('PS_PAYRIGHT_USERNAME', null),
            'PS_PAYRIGHT_CLIENTID'=> Configuration::get('PS_PAYRIGHT_CLIENTID', null),
            'PRODUCTPAGE_PAYRIGHTINSTALLMENTS' => Configuration::get('PS_PAYRIGHT_CLIENTID', null),
            'CATEGORYPAGE_PAYRIGHTINSTALLMENTS' => Configuration::get('CATEGORYPAGE_PAYRIGHTINSTALLMENTS', null),
            'INFOMODAL_TEMPLATE' => Configuration::get('INFOMODAL_TEMPLATE', null) ,
            'PAYRIGHT_MERCHANTUSERNAME' => Configuration::get('PAYRIGHT_MERCHANTUSERNAME', null),
            'PAYRIGHT_MERCHANTPASSWORD' => Configuration::get('PAYRIGHT_MERCHANTPASSWORD', null)
        );  
    }

}