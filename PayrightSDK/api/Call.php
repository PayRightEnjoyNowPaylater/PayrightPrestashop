<?php
/**
 * Payright Payment Status
 *
 * @author Payright
 * @copyright 2016-2019 https://www.payright.com.au
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Payright\api;

use Payright\api\customException;
use GuzzleHttp;

class Call
{

/**
* CONFIG OBJECT
*/

    protected $configObj;
    protected $payrightConfigObj;

    public function __construct()
    {
    }

    public function payRightConfigurationTokenMethod($configobj)
    {
        $token = $configobj->getApiKey();
        $ConfigFields = array();
 
        try {
            $response =  json_decode(
                $this->execute(
                    $configobj->getConfigUrl(),
                    $ConfigFields,
                    $token
                )
            );

            $returnArray = array();
            $returnArray['rates'] = $response->data->rates;
            $returnArray['establishment_fee'] = $response->data->establishmentFees;
            $returnArray['account_keeping_fee'] = $response->data->otherFees->monthlyAccountKeepingFee;
            $returnArray['payment_processing_fee'] = $response->data->otherFees->paymentProcessingFee;

            return $returnArray;
        } catch (customException $e) {
            return $e->errorMessage();
        }
    }


    // This function is not in used as Express Checkout is disabled
    public function payRightTranscationConfigurationTokenMethod($configobj)
    {
        $ConfigFields = array();
       
        try {
            $response =  json_decode(
                $this->execute(
                    $configobj->getConfigUrl(),
                    $ConfigFields,
                    $configobj->getApiKey()
                )
            );

            $returnArray = array();
            $returnArray['auth'] = $response->data->auth;
            $returnArray['configToken'] = $response->data->configToken;
            $returnArray['store_default_term_c'] = $response->data->store_default_term_c;
            $returnArray['store_id'] = $response->data->store_id;
            $returnArray['application_completer_c'] = $response->data->application_completer_c;
            $returnArray['rates'] = $response->data->rates;
            $returnArray['conf'] = $response->data->conf;
            $returnArray['establishment_fee'] = $response->data->establishment_fee;
            $returnArray['client_id'] = $configobj->getClientID();

            return $returnArray;
        } catch (customException $e) {;
            return $e->errorMessage();
        }
    }


    public function initialiseTransaction($orderTotal, $transActionData, $configobj, $redirectUrl)
    {
 
        $paramsPayright = array(
            'applicationCompletedBy' => 'Ecommerce',
            'saleAmount' => number_format((float)$orderTotal, 2, '.', ''),
            'merchantReference' => 'PsPr_'.$transActionData['transactionRef'],
            'redirectUrl' => $redirectUrl,
            'type' => 'standard',
        );
      
        try {
            $response =  $this->execute(
                $configobj->getApiEndPoint(),
                $paramsPayright,
                $configobj->getApiKey(),
                'post'
            );

            return $response;

        } catch (customException $e) {
            return $e->errorMessage();
        }
    }



    /**
        * Execute API Request
        * @param string $apiUrl URL of the endpoint
        * @param array $fields Fields to be sent within the body of the call
        * @param string $tokenAuth User's Access token
        * @param string $requestType (optional) Post, put or null
        * @throws PayrightConfigurationException
        *
        * @return array
     */
    protected function execute($apiURL, $fields, $tokenAuth, $requestType=null)
    {
        $client = new GuzzleHttp\Client();

        try{

            if ($requestType == 'post'){
                $res = $client->post($apiURL, 
                    ['headers' => 
                        [
                            'Content-Type' =>  'application/json', 
                            'Accept' => 'application/json', 
                            'Authorization' => "Bearer {$tokenAuth}"
                        ],
                        'body' => json_encode($fields)
                    ]
                );

            }else if ($requestType == 'put'){

                $res = $client->put($apiURL,
                        ['headers' => 
                            [
                                'Content-Type' =>  'application/json', 
                                'Accept' => 'application/json', 
                                'Authorization' => "Bearer {$tokenAuth}"
                            ],
                            'body' => json_encode($fields)
                        ]
                    );

            }else {
                            
            $res = $client->get($apiURL, 
                ['headers' => 
                    ['Content-Type' =>  'application/json', 
                    'Accept' => 'application/json', 
                    'Authorization' => "Bearer {$tokenAuth}"],
                'body' => json_encode($fields)
                ]
            );

            }

            $response = $res->getBody()->getContents();
            
            return $response;
        } catch (customException $e) {
            return $e->errorMessage();
        }
    }

    /**
     * Function to get a plan's information by its checkout Id
     * @param object configObj
     * @param integer checkoutId
     */

    public function getPlanDataById($configObj, $checkoutId)
    {
        $paramsPayright = array();
            
        try {
            $response =  $this->execute(
                $configObj->getApiEndpoint() . $checkoutId,
                $paramsPayright,
                $configObj->getApiKey()
            );
            return $response;

        } catch (customException $e) {
            return $e->errorMessage();
        }
    }

    /**
    * Create a function for changing the status of a plan to cancel (NOT IN USED)
    * @param object configObj
    * @param integer checkoutId
    * @return array return the plan array with change status
    */


    public function planStatusChange($configObj, $checkoutId)
    {

        $paramsPayright = array();
       
        try {
            $updatePlanStatus =  $this->execute(
                $configObj->getApiEndpoint() . $checkoutId . '/cancel',
                $paramsPayright,
                $configObj->getApiKey(),
                'put'
            );

            $result = json_decode($updatePlanStatus, true);

            return $result;

        } catch (customException $e) {
            return $e->errorMessage();
        }
    }

    /**
    * Function for setting a plan status to Active
    * @param object configObj
    * @param integer checkoutId
    * @return array array returned by the api endpoint
    */

    public function planStatusActivate($configObj, $checkoutId)
    {
        $paramsPayright = array();

        try {
            $updatePlanStatus = $this->execute(
                $configObj->getApiEndpoint() . $checkoutId . '/activate',
                $paramsPayright,
                $configObj->getApiKey(),
                'put'
            );
        
            $result = json_decode($updatePlanStatus, true);

            return $result;
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
}
