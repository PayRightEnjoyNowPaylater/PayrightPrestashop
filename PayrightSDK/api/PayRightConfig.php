<?php
/**
 * Placeholder for Afterpay API Settings
 *
 * @author Payright
 * @copyright 2016-2019 https://www.payright.com.au
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Payright\api;

class PayRightConfig
{
    /**
     * MODE
     */
    protected $mode;

    /**
     * REGION
     */
    protected $region;

    /**
     * API ENDPOINT
     */
    protected $apiEndPoint;

    /**
     * API KEY
     */
    protected $apiKey;

    protected $ConfigUrl;

    public function __construct($payrightMode)
    {

        $this->setConfigParams($payrightMode);
        $this->setEnvironment();
    }
    /**
     * Set Payright URL's based on the selected mode (sandbox or production) and region (au or nz)
     */
    protected function setConfigParams($mode)
    {
        $allConfig = $mode;

        $this->setMode($allConfig['PAYRIGHT_LIVE_MODE']);
        $this->setRegion($allConfig['PAYRIGHT_REGION']);
        $this->setApiKey($allConfig['PS_PAYRIGHT_APIKEY']);

        return true;
    }

    /**
     *
     */
    protected function setEnvironment()
    {
        if ($this->getRegion() == 1){
            $region = 'au';
        }else{
            $region = 'nz';
        }

        if ($this->getMode() == 1) {
            $api_url = 'https://sandbox.payright.com/' . $region . '/checkout/api/v1/';
        } else {
            $api_url = 'https://api.payright.com/' . $region . '/checkout/api/v1/';
        }

        $this->setConfigUrl($api_url . 'merchant/configuration');
        $this->setApiEndpoint($api_url . 'checkouts/');

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
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     *
     * @return self
     */
    public function setRegion($region)
    {
        $this->region = $region;

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
}
