<?php
/**
 * Payright Payment Status
 *
 * @author Payright
 * @copyright 2016-2019 https://www.payright.com.au
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Payright\api;

ini_set("display_errors", "0");

use Payright\api\customException;

class Call
{

/**
* CONFIG OBJECT
*/

    protected $configObj;
    protected $payrightAuthObj;
    protected $payrightConfigObj;

    public function getCallEndApi()
    {
        $curlURL = $this->apiConfigObj->getOrderUrl();
        $curlResponse = $this->execute($curlURL, 'POST');
        return $curlResponse;
    }


    public function __construct()
    {
    }

    public function payRightAuth($configObj)
    {
        $AuthFields = array(
        'username' => $configObj->getUsername(),
        'password' => $configObj->getPassword(),
        'grant_type' => 'password',
        'client_id' => $configObj->getClientID(),
        'client_secret' => $configObj->getApiKey()
        //'client_secret' => uBtLxIXPUMs4a0l0ViqxP1QVBGr62FG8YIGi5iMl
        );

      

        try {
            $responseAuth =  $this->execute($configObj->getAuthUrl(), $AuthFields, false, null);
           
            ## now set this object
            $this->setPayrightAuthObj($responseAuth);
            return $responseAuth;
        } catch (customException $e) {
            return $e->errorMessage();
        }
    }


    public function payRightConfigurationTokenMethod($cookieObj, $configobj, $paraAccessToken)
    {
        $ConfigFields = array(
        'merchantusername' => $configobj->getMerchantusername(),
        'merchantpassword' => $configobj->getMerchantpassword()
        );

      
       
        try {
            $response =  json_decode(
                $this->execute(
                    $configobj->getConfigUrl(),
                    $ConfigFields,
                    "Bearer",
                    $paraAccessToken
                )
            );

            $returnArray = array();
            $returnArray['auth'] = $response->data->auth;
            $returnArray['configToken'] = $response->data->configToken;
            $returnArray['store'] = $response->data->store;
            $returnArray['rates'] = $response->data->rates;
            $returnArray['conf'] = $response->data->conf;
            $returnArray['establishment_fee'] = $response->data->establishment_fee;
            $returnArray['client_id'] = $configobj->getClientID();

            print_r($response);
            die;
            $this->setSessionValues($response, $cookieObj);
            return $returnArray;
        } catch (customException $e) {
            return $e->errorMessage();
        }
    }


    public function intializeTransaction($orderTotal, $accessToken, $transActionData, $configobj)
    {
        $transactionDataArray = array();
        $transactionDataArray['platform_type'] = 'prestashop';
        $transactionDataArray['transactionTotal'] = number_format((float)$orderTotal, 2, '.', '');
        $transactionDataArray['merchantreference'] = $transActionData['transactionRef'];

        if (!isset($_REQUEST['sugarauthtoken'])) {
            $sugarAuthToken = $transActionData['sugarAuthToken'];
        } else {
            $sugarAuthToken = $_REQUEST['sugarauthtoken'];
        }

        if (!isset($_REQUEST['configtoken'])) {
            $configToken = $transActionData['configToken'];
        } else {
            $configToken = $_REQUEST['configtoken'];
        }
 
        $paramsPayright = array(
        'Token' => $sugarAuthToken,
        'ConfigToken' =>   $configToken,
        'transactiondata' => json_encode($transactionDataArray),
        'totalAmount' => number_format((float)$orderTotal, 2, '.', ''),
        'merchantReference' => $transActionData['transactionRef'],
        'clientId' => $transActionData['clientId']
        );

      
        try {
            $response =  $this->execute(
                $configobj->getIntialiseTransactionUrl(),
                $paramsPayright,
                "Bearer",
                $accessToken
            );
            return $response;
        } catch (customException $e) {
            return $e->errorMessage();
        }
    }



    /**
        * Execute API Request
        * @param string $curlURL:q!
        * @param string $curlMethod
        * @param Object $dataObject
        * @throws PayrightConfigurationException
        *
        * @return array
     */
    protected function execute($curlURL, $fields, $tokenAuth, $paraAccess_token = null)
    {
        //Check if CURL module exists.
        if (!function_exists("curl_init")) {
            return "error";
        }

        //url-ify the data for the POST
        $fields_string = '';
        foreach ($fields as $key => $value) {
            $fields_string .= $key.'='.$value.'&';
        }
        rtrim($fields_string, '&');

        //open connection
        try {
            $ch = curl_init();

            if ($tokenAuth == 'Bearer') {
                //$authObj = json_decode($this->getPayrightAuthObj());
                if ($paraAccess_token == null) {
                    $access_token = '';
                } else {
                    $access_token = $paraAccess_token;
                }


                // return;

                $headers = array(
                "Authorization: Bearer ".$access_token,
                );

                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }

            curl_setopt_array($ch, array(
              CURLOPT_URL => $curlURL,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => $fields_string
            ));

            $response = curl_exec($ch);
            $err = curl_error($ch);



            curl_close($ch);

            if ($err) {
                return "error";
            } else {
                return $response;
            }
        } catch (customException $e) {
            return $e->errorMessage();
        }
    }


    public function getPlanDataByToken($ecommerceToken, $configObj)
    {
        $paramsPayright = array(
        'ecomToken' => $ecommerceToken
        );

        $payRightAuthToken = $this->payRightAuth($configObj);
        $payRightAuthObj = json_decode($payRightAuthToken);

        try {
            $response =  $this->execute(
                $configObj->getEcomTokenDataUrl(),
                $paramsPayright,
                "Bearer",
                $payRightAuthObj->access_token
            );
            return $response;
        } catch (customException $e) {
            return $e->errorMessage();
        }
    }

    /**
        * Create a function for change the status of plan
        * @param integer PlanId
        * @return array return the plan array with change status
        */


    public function planStatusChange($configObj, $planId)
    {
        $payRightAuthToken = $this->payRightAuth($configObj);
        $payRightAuthObj = json_decode($payRightAuthToken);

        $ConfigFields = array(
        'merchantusername' => $configObj->getMerchantusername(),
        'merchantpassword' => $configObj->getMerchantpassword()
         );
       
        $response =  json_decode($this->execute(
            $configObj->getConfigUrl(),
            $ConfigFields,
            "Bearer",
            $payRightAuthObj->access_token
        ));
        $paramsPayright = array(
        'id' => $planId,
        'status' => 'Cancelled',
        'Token' => $response->data->auth->{'auth-token'}
        );
       
        try {
            $updatePlanStatus =  $this->execute(
                $configObj->setPlanStatusChangeUrl(),
                $paramsPayright,
                "Bearer",
                $payRightAuthObj->access_token
            );
            return $updatePlanStatus;
        } catch (customException $e) {
            return $e->errorMessage();
        }
    }


    /**
     * @return mixed
     */
    public function getConfigObj()
    {
        return $this->configObj;
    }

    /**
     * @param mixed $configObj
     *
     * @return self
     */
    public function setConfigObj($configObj)
    {
        $this->configObj = $configObj;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPayrightAuthObj()
    {
        return $this->payrightAuthObj;
    }

    /**
     * @param mixed $payrightAuthObj
     *
     * @return self
     */
    public function setPayrightAuthObj($payrightAuthObj)
    {
        $this->payrightAuthObj = $payrightAuthObj;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPayrightConfigObj()
    {
        return $this->payrightConfigObj;
    }

    /**
     * @param mixed $payrightConfigObj
     *
     * @return self
     */
    public function setPayrightConfigObj($payrightConfigObj)
    {
        $this->payrightConfigObj = $payrightConfigObj;

        return $this;
    }


    public function setSessionValues($configValues, $cookieObj)
    {
        $cookieObj->AccountKeepingfees = $configValues->data->conf->{'Monthly Account Keeping Fee'};

        print_r($configValues->data);
        $cookieObj->PayrightRates = serialize($configValues->data->rates);
        $cookieObj->establishmentFeeArray = serialize($configValues->data->establishment_fee);
        $cookieObj->PaymentProcessingFee = $configValues->data->conf->{'Payment Processing Fee'};
    }
}
