<?php
/**
 * Placeholder for Afterpay API Settings
 *
 * @author Payright
 * @copyright 2016-2019 https://www.payright.com.au
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Payright\api;

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

class PayRightConfig
{
    /**
    * MODE
    */
    protected $mode;

    /**
    * API ENDPOINT
    */
    protected $apiEndPoint;

    /**
    * API KEY
    */
    protected $apiKey;

    /**
    * CLIENT ID
    */
    protected $clientID;

    /**
    * USERNAME
    */
    protected $username;

    /**
    * password
    */
    protected $password;

    /**
    * merchantusername
    */
    protected $merchantusername;

    /**
    * merchantpassword
    */
    protected $merchantpassword;


    protected $AuthUrl;

    protected $ConfigUrl;

    protected $intialiseTransactionUrl;
    




    public function __construct($payrightMode)
    {
        
        $this->setConfigParams($payrightMode);
        $this->setEnvironment('dev');

       /* if (empty($input)) {
            $filename = _PS_MODULE_DIR_.'payright/PayrightSDK/config/config.ini';

            print $filename;


            //Check if all parameters are set properly in config.ini
            $validateConfigResponse = $this->validateConfigFile($filename);

           // print_r($payrightMode);

            //If Config.ini is valid and all values are set properly.
            if ($validateConfigResponse) {
                /// setting the enviroment
                $this->setEnvironment('dev');
                $this->setConfigParams($payrightMode);
            } else {
                echo "Please check if config.ini exists and all values are set properly.
                Please make sure mode should be either sandbox or production";
                exit;
            }
            // $this->setEnvironment($ini_array['Service']['mode']);
        } else {
            // if( !empty($input['mode']) ) {
            //     $this->setMode($input['mode']);
            // }
            // if( !empty($input['merchantId']) ) {
            //     $this->setMerchantId($input['merchantId']);
            // }
            // if( !empty($input['merchantSecret']) ) {
            //     $this->setMerchantSecret($input['merchantSecret']);
            // }
            // if( !empty($input['mode']) ) {
            //     $this->setEnvironment($input['mode']);
            // }
            // if( !empty($input['sdkName']) ) {
            //     $this->setSDKName($input['sdkName']);
            // }
            // if( !empty($input['sdkVersion']) ) {
            //     $this->setSDKVersion($input['sdkVersion']);
            // }
        }*/
    }
    /*
    * Validate config.ini array and values
    * params config.ini file name $fileName
    * Returns boolean
    */
    protected function validateConfigFile($fileName)
    {
        //Check if config.ini file exists in the root directory
        // echo $fileName;
        
        if (!file_exists($fileName)) {
            return false;
        }
        //Parse config.ini file to set configuration variables.
        $ini_array = parse_ini_file($fileName, true);
        //Check if Service mode exists. Can be either sandbox or production.
        if (!array_key_exists("Service", $ini_array)) {
            return false;
        }
        //Check if Service is not sandbox or production
        if ($ini_array['Service']['mode'] != "sandbox" && $ini_array['Service']['mode'] != "production") {
            return false;
        }
        //Check if Account Key exists
        if (!array_key_exists("Account", $ini_array)) {
            return false;
        }
        //Check if Merchant Id is blank
        if ($ini_array['Account']['MerchantId'] == '') {
            return false;
        }
        //Check if Merchant Secret is blank
        if ($ini_array['Account']['MerchantSecret'] == '') {
            return false;
        }
        //If all validations are correct, return true
        return true;
    }
    /**
    * Set Payright URL's based on the selected mode (sandbox or production)
    */
    protected function setConfigParams($mode)
    {
        $allConfig = $mode;

        //print_r($allConfig);

        $this->setUsername($allConfig['PS_PAYRIGHT_USERNAME']);
        $this->setMode($allConfig['PAYRIGHT_LIVE_MODE']);
        $this->setApiKey($allConfig['PS_PAYRIGHT_APIKEY']);
        $this->setClientID($allConfig['PS_PAYRIGHT_CLIENTID']);
        $this->setMerchantusername($allConfig['PAYRIGHT_MERCHANTUSERNAME']);
        $this->setMerchantpassword($allConfig['PAYRIGHT_MERCHANTPASSWORD']);
        $this->setPassword($allConfig['PAYRIGHT_ACCOUNT_PASSWORD']);
        return true;
    }


    /**
    *
    */
    protected function setEnvironment($mode)
    {
        //Set Merchant Id, Secret Key and Callback Url
        
        if ($mode == 'dev') {
            $this->setAuthUrl('http://ecommerceapi.payright.local/oauth/token');
            $this->setConfigUrl('http://ecommerceapi.payright.local/api/v1/configuration');
            $this->setIntialiseTransactionUrl('http://ecommerceapi.payright.local/api/v1/intialiseTransaction');
            $this->setEcomTokenDataUrl('http://ecommerceapi.payright.local/api/v1/getEcomTokenData');
            $this->setEcomUrl('http://customerpayrightportal.local/loan/new/');
            $this->setPlanStatusChangeUrl('http://ecommerceapi.payright.local/api/v1/changePlanStatus');
        } elseif ($mode == 'sandbox') {
            $this->setAuthUrl('https://betaonlineapi.payright.com.au/oauth/token');
            $this->setConfigUrl('https://betaonlineapi.payright.com.au/api/v1/configuration');
            $this->setIntialiseTransactionUrl('https://betaonlineapi.payright.com.au/api/v1/intialiseTransaction');
            $this->setEcomTokenDataUrl('https://betaonlineapi.payright.com.au/api/v1/getEcomTokenData');
            $this->setEcomUrl('https://betaonline.payright.com.au/loan/new/');
            $this->setPlanStatusChangeUrl('https://betaonlineapi.payright.com.au/api/v1/changePlanStatus');
        } elseif ($mode == 'beta') {
            $this->setAuthUrl('https://betaonlineapi.payright.com.au/oauth/token');
            $this->setConfigUrl('https://betaonlineapi.payright.com.au/api/v1/configuration');
            $this->setIntialiseTransactionUrl('https://betaonlineapi.payright.com.au/api/v1/intialiseTransaction');
            $this->setEcomTokenDataUrl('https://betaonlineapi.payright.com.au/api/v1/getEcomTokenData');
            $this->setEcomUrl('https://betaonline.payright.com.au/loan/new/');
            $this->setPlanStatusChangeUrl('https://betaonlineapi.payright.com.au/api/v1/changePlanStatus');
        }

        return $this;
    }

       
   

    /**
     * @return mixed
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param mixed $mode
     *
     * @return self
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiEndPoint()
    {
        return $this->apiEndPoint;
    }

    /**
     * @param mixed $apiEndPoint
     *
     * @return self
     */
    public function setApiEndPoint($apiEndPoint)
    {
        $this->apiEndPoint = $apiEndPoint;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param mixed $apiKey
     *
     * @return self
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientID()
    {
        return $this->clientID;
    }

    /**
     * @param mixed $clientID
     *
     * @return self
     */
    public function setClientID($clientID)
    {
        $this->clientID = $clientID;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMerchantusername()
    {
        return $this->merchantusername;
    }

    /**
     * @param mixed $merchantusername
     *
     * @return self
     */
    public function setMerchantusername($merchantusername)
    {
        $this->merchantusername = $merchantusername;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMerchantpassword()
    {
        return $this->merchantpassword;
    }

    /**
     * @param mixed $merchantpassword
     *
     * @return self
     */
    public function setMerchantpassword($merchantpassword)
    {
        $this->merchantpassword = $merchantpassword;

        return $this;
    }





    /**
     * @return mixed
     */
    public function getAuthUrl()
    {
        return $this->AuthUrl;
    }

    /**
     * @param mixed $AuthUrl
     *
     * @return self
     */
    public function setAuthUrl($AuthUrl)
    {
        $this->AuthUrl = $AuthUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfigUrl()
    {
        return $this->ConfigUrl;
    }

    /**
     * @param mixed $ConfigUrl
     *
     * @return self
     */
    public function setConfigUrl($ConfigUrl)
    {
        $this->ConfigUrl = $ConfigUrl;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getIntialiseTransactionUrl()
    {
        return $this->intialiseTransactionUrl;
    }

    /**
     * @param mixed $intialiseTransactionUrl
     *
     * @return self
     */
    public function setIntialiseTransactionUrl($intialiseTransactionUrl)
    {
        $this->intialiseTransactionUrl = $intialiseTransactionUrl;

        return $this;
    }


     /**
     * @return mixed
     */
    public function getEcomTokenDataUrl()
    {
        return $this->ecomTokenDataUrl;
    }

    /**
     * @param mixed $intialiseTransactionUrl
     *
     * @return self
     */
    public function setEcomTokenDataUrl($ecomTokenDataUrl)
    {
        $this->ecomTokenDataUrl = $ecomTokenDataUrl;

        return $this;
    }

     /**
     * @return mixed
     */
    public function getEcomUrl()
    {
        return $this->ecomUrl;
    }

    /**
     * @param mixed $intialiseTransactionUrl
     *
     * @return self
     */
    public function setEcomUrl($ecomUrl)
    {
        $this->ecomUrl = $ecomUrl;

        return $this;
    }

        /**
     * @return mixed
     */
    public function getPlanStatusChangeUrl()
    {
        return $this->planStatusChangeUrl;
    }

    /**
     * @param mixed $intialiseTransactionUrl
     *
     * @return self
     */
    public function setPlanStatusChangeUrl($planStatusChangeUrl)
    {
        $this->planStatusChangeUrl = $planStatusChangeUrl;

        return $this;
    }
}
