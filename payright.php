<?php
/**
 * Placeholder for Payright module
 *
 * @author Payright
 * @copyright 2016-2019 https://www.payright.com.au
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

require_once(_PS_MODULE_DIR_.'payright/PayrightSDK/api/PayRightConfig.php');
require_once(_PS_MODULE_DIR_.'payright/PayrightSDK/api/Call.php');
require_once(_PS_MODULE_DIR_.'payright/PayrightSDK/api/Calculations.php');


use Payright\api\PayRightConfig;
use PrestaShop\PrestaShop\Core\Payment\PaymentOption;
use Payright\api\Call;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Payright extends PaymentModule
{
    protected $html = '';
    protected $postErrors = array();

    public $details;
    public $owner;
    public $address;
    public $extra_mail_vars;

    public function __construct()
    {
        $this->name = 'payright';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.1';
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->author = 'PrestaShop';
        $this->controllers = array('validation');
        $this->is_eu_compatible = 1;

        $this->currencies = true;
        $this->currencies_mode = 'checkbox';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Payright');
        $this->description = $this->l('Enjoy Now. Pay Later');
      

        if (!count(Currency::checkPaymentCurrencies($this->id))) {
            $this->warning = $this->l('No currency has been set for this module.');
        }
    }

    public function install()
    {
        $this->registerHook('backOfficeHeader');
        $this->registerHook('header');
        $this->registerHook('displayShoppingCartFooter');
        $this->registerHook('displayNavFullWidth');
        $this->registerHook('actionCartSave');
        
      
        if (!parent::install() || !$this->registerHook('paymentOptions') || !$this->registerHook('paymentReturn')
            || !$this->registerHook('displayProductPriceBlock')
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayShoppingCartFooter')
            || !$this->registerHook('displayNavFullWidth')
            || !$this->registerHook('actionCartSave')
            || !Configuration::updateValue('PAYRIGHT_LIVE_MODE', '')
            || !Configuration::updateValue('PAYRIGHT_ACCOUNT_EMAIL', '')
            || !Configuration::updateValue('PAYRIGHT_ACCOUNT_PASSWORD', '')
            || !Configuration::updateValue('PS_PAYRIGHT_USERNAME', '')
            || !Configuration::updateValue('PS_PAYRIGHT_CLIENTID', '')
            || !Configuration::updateValue('CATEGORYPAGE_PAYRIGHTINSTALLMENTS', '')
            || !Configuration::updateValue('INFOMODAL_TEMPLATE', '')
            || !Configuration::updateValue('PAYRIGHT_MERCHANTPASSWORD', '')
        ) {
            return false;
        }
        return true;
    }

    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return;
        }

        if (!$this->checkCurrency($params['cart'])) {
            return;
        }

        $payment_options = array(
            $this->getExternalPaymentOption()
        );

        return $payment_options;
    }

    public function checkCurrency($cart)
    {
        $currency_order = new Currency($cart->id_currency);
        $currencies_module = $this->getCurrency($cart->id_currency);

        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getOfflinePaymentOption()
    {
        $offlineOption = new PaymentOption();
        $offlineOption->setCallToActionText($this->l('Pay offline'))
                      ->setAction($this->context->link->getModuleLink($this->name, 'validation', array(), true))
                      ->setAdditionalInformation($this->context->smarty->
                        fetch('module:payright/views/templates/front/payment_infos.tpl'))
                      ->setLogo(Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/payment.jpg'));

        return $offlineOption;
    }

    public function getExternalPaymentOption()
    {
        $ConfigValues = $this->getConfigFormValues();
        $PayRightConfig = new Payright\api\PayRightConfig($ConfigValues, null);
      
        $PayRightApiCall = new Payright\api\Call($PayRightConfig);
        $PayRightCalculations = new Payright\api\Calculations();

       

        ### perform the api authentication

        $payRightAuth =  $PayRightApiCall->payRightAuth($PayRightConfig);
        $payRightAuthObj = json_decode($payRightAuth);
        
        ### now do the config call.
        $payRightConfig = $PayRightApiCall->payRightConfigurationTokenMethod(
            $this->context->cookie,
            $PayRightConfig,
            $payRightAuthObj->access_token
        );


     
        $sugarAuthToken = $payRightConfig['auth']->{'auth-token'};
        $configToken =  $payRightConfig['configToken'];

        $orderTotal = $this->context->cart->getOrderTotal();



        $PayrightCalculations =  $PayRightCalculations->calculateSingleProductInstallment(
            $this->context->cookie->PayrightRates,
            $orderTotal,
            $this->context->cookie
        );


        $this->context->smarty->assign($PayrightCalculations);
        $externalOption = new PaymentOption();

        $externalOption->setCallToActionText($this->l('Payright - Interest Free Payments'))
                       ->setAction($this->context->link->getModuleLink(
                           $this->name,
                           'validation',
                           array(),
                           true
                       ))
                       ->setInputs(array(
                            'payrightauthtoken' => array(
                                'name' =>'payrightauth',
                                'type' =>'hidden',
                                'value' => $payRightAuthObj->access_token
                            ),
                            'sugarauthtoken' => array(
                                'name' =>'sugarauthtoken',
                                'type' =>'hidden',
                                'value' => $sugarAuthToken
                            ),
                            'configtoken' => array(
                                'name' =>'configtoken',
                                'type' =>'hidden',
                                'value' => $configToken
                            ),
                            'clientid' => array(
                                'name' =>'clientid',
                                'type' =>'hidden',
                                'value' => $ConfigValues['PS_PAYRIGHT_CLIENTID']
                            )
                        ))
                       ->setAdditionalInformation(
                           $this->context->smarty->fetch('module:payright/views/templates/front/payment_infos.tpl')
                       )
                       ->setLogo(Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/views/img/payrightpayment.png'));

        return $externalOption;
    }

    public function getEmbeddedPaymentOption()
    {
        $embeddedOption = new PaymentOption();
        $embeddedOption->setCallToActionText($this->l('Pay embedded'))
                       ->setForm($this->generateForm())
                       ->setAdditionalInformation(
                           $this->context->smarty->fetch('module:payright/views/templates/front/payment_infos.tpl')
                       )
                       ->setLogo(Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/payment.jpg'));

        return $embeddedOption;
    }

    public function getIframePaymentOption()
    {
        $iframeOption = new PaymentOption();
        $iframeOption->setCallToActionText($this->l('Pay iframe'))
                     ->setAction($this->context->link->getModuleLink($this->name, 'iframe', array(), true))
                     ->setAdditionalInformation(
                         $this->context->smarty->fetch('module:payright/views/templates/front/payment_infos.tpl')
                     )
                     ->setLogo(Media::getMediaPath(_PS_MODULE_DIR_.$this->name.'/payment.jpg'));

        return $iframeOption;
    }

    protected function generateForm()
    {
        $months = array();
        for ($i = 1; $i <= 12; $i++) {
            $months[] = sprintf("%02d", $i);
        }

        $years = array();
        for ($i = 0; $i <= 10; $i++) {
            $years[] = date('Y', strtotime('+'.$i.' years'));
        }

        $this->context->smarty->assign(array(
            'action' => $this->context->link->getModuleLink($this->name, 'validation', array(), true),
            'months' => $months,
            'years' => $years,
        ));

        return $this->context->smarty->fetch('module:payright/views/templates/front/payment_form.tpl');
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitPayrightModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        $productPageOptions = array(
        array(
            'id_option' => 1,       // The value of the 'value' attribute of the <option> tag.
            'name' => 'Yes'    // The value of the text content of the  <option> tag.
        ),
        array(
            'id_option' => 2,
            'name' => 'No'
        ),
        );


        $categoryPageOptions = array(
        array(
            'id_option' => 1,       // The value of the 'value' attribute of the <option> tag.
            'name' => 'Yes'    // The value of the text content of the  <option> tag.
        ),
        array(
            'id_option' => 2,
            'name' => 'No'
        ),
        );

        $relatedProductsOptions = array(
        array(
            'id_option' => 1,       // The value of the 'value' attribute of the <option> tag.
            'name' => 'Yes'    // The value of the text content of the  <option> tag.
        ),
        array(
            'id_option' => 2,
            'name' => 'No'
        ),
        );

        $frontPageOptions = array(
        array(
            'id_option' => 1,       // The value of the 'value' attribute of the <option> tag.
            'name' => 'Yes'    // The value of the text content of the  <option> tag.
        ),
        array(
            'id_option' => 2,
            'name' => 'No'
        ),
        );

        $cartPageOptions = array(
        array(
            'id_option' => 1,       // The value of the 'value' attribute of the <option> tag.
            'name' => 'Yes'    // The value of the text content of the  <option> tag.
        ),
        array(
            'id_option' => 2,
            'name' => 'No'
        ),
        );


        $modalOptions = array(
        array(
            'id_option' => 1,       // The value of the 'value' attribute of the <option> tag.
            'name' => 'Modal 1'    // The value of the text content of the  <option> tag.
        )
        );




        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('SANDBOX'),
                        'name' => 'PAYRIGHT_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in sandbox  mode'),
                        'required' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                      'type'     => 'text',
                      'label'    => $this->l('API KEY'),
                      'name'     => 'PS_PAYRIGHT_APIKEY',
                      'col'    => '3',
                      'required' => true,
                      'desc'     => $this->l('Please enter your API Key')
                    ),
                    array(
                      'type'     => 'text',
                      'label'    => $this->l('CLIENT ID'),
                      'name'     => 'PS_PAYRIGHT_CLIENTID',
                      'col'    => '3',
                      'required' => true,
                      'desc'     => $this->l('Please enter your Client ID')
                    ),
                     array(
                      'type'     => 'text',
                      'label'    => $this->l('USERNAME'),
                      'name'     => 'PS_PAYRIGHT_USERNAME',
                      'col'    => '3',
                      'required' => true,
                      'desc'     => $this->l('Please enter your username provided by Payright')
                    ),
                    array(
                        'type' => 'password',
                        'name' => 'PAYRIGHT_ACCOUNT_PASSWORD',
                        'label' => $this->l('Password'),
                        'col' => '3',
                        'required' => true
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Enter your merchant username'),
                        'name' => 'PAYRIGHT_MERCHANTUSERNAME',
                        'label' => $this->l('Merchant Username'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'desc' => $this->l('Enter your merchant password'),
                        'name' => 'PAYRIGHT_MERCHANTPASSWORD',
                        'label' => $this->l('Merchant Password'),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Product Page'),
                        'desc' => $this->l('Show Payright Installments information on product page'),
                        'name' => 'PRODUCTPAGE_PAYRIGHTINSTALLMENTS',
                        'required' => true,
                        'options' => array(
                            'query' => $productPageOptions,
                              'id' => 'id_option',
                              'name' => 'name'
                            )
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Category Page'),
                        'desc' => $this->l('Show Payright Installments information on category page'),
                        'name' => 'CATEGORYPAGE_PAYRIGHTINSTALLMENTS',
                        'required' => true,
                        'options' => array(
                            'query' => $categoryPageOptions,
                              'id' => 'id_option',
                              'name' => 'name'
                            )
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Cart Page'),
                        'desc' => $this->l('Show Payright Installments information on cart page'),
                        'name' => 'CARTPAGE_PAYRIGHTINSTALLMENTS',
                        'required' => true,
                        'options' => array(
                            'query' => $cartPageOptions,
                              'id' => 'id_option',
                              'name' => 'name'
                            )
                    ),

                    array(
                        'type' => 'select',
                        'label' => $this->l('Front Page'),
                        'desc' => $this->l('Show Payright Installments information on front page'),
                        'name' => 'FRONTPAGE_PAYRIGHTINSTALLMENTS',
                        'required' => true,
                        'options' => array(
                            'query' => $frontPageOptions,
                              'id' => 'id_option',
                              'name' => 'name'
                            )
                    ),

                    array(
                        'type' => 'select',
                        'label' => $this->l('Related Products'),
                        'desc' => $this->l('Show Payright Installments information on related products'),
                        'name' => 'RELATEDPRODUCTS_PAYRIGHTINSTALLMENTS',
                        'required' => true,
                        'options' => array(
                            'query' => $relatedProductsOptions,
                              'id' => 'id_option',
                              'name' => 'name'
                            )
                    ),

                      array(
                        'type' => 'select',
                        'label' => $this->l('Payright Modal Option'),
                        'desc' => $this->l('Pick info modal template'),
                        'name' => 'INFOMODAL_TEMPLATE',
                        'required' => true,
                        'options' => array(
                            'query' => $modalOptions,
                              'id' => 'id_option',
                              'name' => 'name'
                            )
                    )
                    
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'PAYRIGHT_LIVE_MODE' => Configuration::get('PAYRIGHT_LIVE_MODE', true),
            'PAYRIGHT_ACCOUNT_EMAIL' => Configuration::get('PAYRIGHT_ACCOUNT_EMAIL', 'contact@prestashop.com'),
            'PAYRIGHT_ACCOUNT_PASSWORD' => Configuration::get('PAYRIGHT_ACCOUNT_PASSWORD', null),
            'PS_PAYRIGHT_APIKEY' => Configuration::get('PS_PAYRIGHT_APIKEY', null),
            'PS_PAYRIGHT_USERNAME'=> Configuration::get('PS_PAYRIGHT_USERNAME', null),
            'PS_PAYRIGHT_CLIENTID'=> Configuration::get('PS_PAYRIGHT_CLIENTID', null),
            'PRODUCTPAGE_PAYRIGHTINSTALLMENTS' => Configuration::get('PRODUCTPAGE_PAYRIGHTINSTALLMENTS', null),
            'CATEGORYPAGE_PAYRIGHTINSTALLMENTS' => Configuration::get('CATEGORYPAGE_PAYRIGHTINSTALLMENTS', null),
            'CARTPAGE_PAYRIGHTINSTALLMENTS' => Configuration::get('CARTPAGE_PAYRIGHTINSTALLMENTS', null),
            'FRONTPAGE_PAYRIGHTINSTALLMENTS' => Configuration::get('FRONTPAGE_PAYRIGHTINSTALLMENTS', null),
            'RELATEDPRODUCTS_PAYRIGHTINSTALLMENTS' => Configuration::get('RELATEDPRODUCTS_PAYRIGHTINSTALLMENTS', null),
            'INFOMODAL_TEMPLATE' => Configuration::get('INFOMODAL_TEMPLATE', null),
            'PAYRIGHT_MERCHANTUSERNAME' => Configuration::get('PAYRIGHT_MERCHANTUSERNAME', null),
            'PAYRIGHT_MERCHANTPASSWORD' => Configuration::get('PAYRIGHT_MERCHANTPASSWORD', null)

        );
    }
    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.s
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookActionCartSave()
    {
        $getSessionValue = $this->getSessionValue();
        $ConfigValues = $this->getConfigFormValues();
        $cartInstalments = $ConfigValues['CARTPAGE_PAYRIGHTINSTALLMENTS'];


        if (isset($this->context->cookie->access_token)) {
            $sugarAuthToken= $getSessionValue['auth']->{'auth-token'};
            $configToken = $getSessionValue['configToken'];
            $payrightAccessToken = $this->context->cookie->access_token;
   
            $clientId = $getSessionValue['client_id'];

            $cart = $this->context->cart;

            $allowPlan = $this->getCurrentInstalmentsDisplay($cart->getOrderTotal());

            $PayRightApiCall = new Payright\api\Call();


            $ConfigValues = $this->getConfigFormValues();
            $PayRightConfig = new Payright\api\PayRightConfig($ConfigValues, null);

            $transactionData = array();
            $transactionData['transactionRef'] = $cart->id."_".$clientId.rand();
            $transactionData['clientId'] = $clientId;
            $transactionData['sugarAuthToken'] = $sugarAuthToken;
            $transactionData['configToken'] = $configToken;

            $intializeTransaction = $PayRightApiCall->intializeTransaction(
                $cart->getOrderTotal(),
                $payrightAccessToken,
                $transactionData,
                $PayRightConfig
            );
            $intializeTransactionData = json_decode($intializeTransaction);

            $ecommToken = $intializeTransactionData->ecommToken;

            $moduleShow = true;

            if (isset($allowPlan['noofrepayments'])) {
                $this->context->smarty->assign('repayment', $allowPlan['noofrepayments']);
                $this->context->smarty->assign('installment', $allowPlan['LoanAmountPerPayment']);
            }

            
            $this->context->smarty->assign('redirectUrl', $PayRightConfig->ecomUrl.$ecommToken);
        } else {
            $moduleShow = false;
        }

 
        if ($moduleShow == 1 && $allowPlan != 'exceed_amount' && $cartInstalments == 1) {
            return  $this->context->smarty->fetch("module:payright/views/templates/hook/cart_payright.tpl");
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookDisplayHeader()
    {
        $this->getSessionValue = $this->getSessionValue();

        if ($this->getSessionValue == "error") {
            $this->context->cookie->error = $this->getSessionValue;
        } else {
            $this->context->cookie->error = '';
        }

        $this->context->smarty->assign("payright_base_url", Context::getContext()->shop->getBaseURL(true));
    }

    /**
     * [hookDisplayNavFullWidth description]
     * @return error template
     */
    public function hookDisplayNavFullWidth()
    {
        if ($this->context->cookie->error == "error") {
            return $this->context->smarty->fetch("module:payright/views/templates/front/error.tpl");
        }
    }

    public function hookDisplayShoppingCartFooter($params)
    {
        $installmentResult = $this->getPayrightInstallments();
        if ($installmentResult['moduleShow'] == 1 && $installmentResult['allowPlan'] != 'exceed_amount') {
            return  $this->context->smarty->fetch("module:payright/views/templates/hook/cart_payright.tpl");
        }
    }


    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }


    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
    
        if (((bool)Tools::isSubmit('submitPayrightModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);
        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
        return $output.$this->renderForm();
    }



    /*-----------------------------------------------------------------------------------------------------------------------
                                                    PayRight Product Display
    -----------------------------------------------------------------------------------------------------------------------*/

    /**
    * Function to display the PayRight Product Price Payment Breakdown
    * @param array $params
    * @return TPL
    * since 1.0.0
    */
    public function hookDisplayProductPriceBlock($params)
    {
        $current_controller = Tools::getValue('controller');
        $ConfigValues = $this->getConfigFormValues();

        $templateValue = $ConfigValues['INFOMODAL_TEMPLATE'];
        $productInstallments = $ConfigValues['PRODUCTPAGE_PAYRIGHTINSTALLMENTS'];
        $categoryInstalments = $ConfigValues['CATEGORYPAGE_PAYRIGHTINSTALLMENTS'];
        $frontpageInstalments = $ConfigValues['FRONTPAGE_PAYRIGHTINSTALLMENTS'];
        $relatedProductsInstalments = $ConfigValues['RELATEDPRODUCTS_PAYRIGHTINSTALLMENTS'];



        if ($current_controller == 'category'  && $params["type"] == 'unit_price' && $categoryInstalments == 1) {
            $payRightInstallmentBreakDown =  $this->getCurrentInstalmentsDisplay($params["product"]["price_amount"]);

            if ($payRightInstallmentBreakDown != 'exceed_amount') {
                $this->context->smarty->assign("payright_instalment_breakdown", $payRightInstallmentBreakDown);
            }
            return $this->context->smarty->fetch("module:payright/views/templates/front/product_thumbnail.tpl");
        }
        if ($current_controller == "product" && $params["type"] == "after_price" && $productInstallments == 1) {
            $payRightInstallmentBreakDown =  $this->getCurrentInstalmentsDisplay($params["product"]["price_amount"]);

            if ($payRightInstallmentBreakDown != 'exceed_amount') {
                $this->context->smarty->assign("payright_instalment_breakdown", $payRightInstallmentBreakDown);
            }
            $this->context->smarty->assign("templateValue", $templateValue);
            return $this->context->smarty->fetch("module:payright/views/templates/front/product_page.tpl");
        }

        if ($current_controller == 'product'  && $params["type"] == 'unit_price' && $relatedProductsInstalments == 1) {
            // for related products
            $payRightInstallmentBreakDown =  $this->getCurrentInstalmentsDisplay($params["product"]["price_amount"]);
            if ($payRightInstallmentBreakDown != 'exceed_amount') {
                $this->context->smarty->assign("payright_instalment_breakdown", $payRightInstallmentBreakDown);
            }
            return $this->context->smarty->fetch("module:payright/views/templates/front/product_thumbnail.tpl");
        }

        if ($current_controller == "index" && $params["type"] == "unit_price" && $frontpageInstalments == 1) {
            $payRightInstallmentBreakDown =  $this->getCurrentInstalmentsDisplay($params["product"]["price_amount"]);

            if ($payRightInstallmentBreakDown != 'exceed_amount') {
                $this->context->smarty->assign("payright_instalment_breakdown", $payRightInstallmentBreakDown);
            }
            $this->context->smarty->assign("templateValue", $templateValue);
            return $this->context->smarty->fetch("module:payright/views/templates/front/product_thumbnail.tpl");
        }
    }

    

    public function getCurrentInstalmentsDisplay($productTotal)
    {
        $rateCard = $this->context->cookie->PayrightRates;
        $rateUnserialized = unserialize($rateCard);
        if (!empty($rateUnserialized)) {
            $PayRightCalculations = new Payright\api\Calculations();
            $PayrightCalculations =  $PayRightCalculations->calculateSingleProductInstallment(
                $this->context->cookie->PayrightRates,
                $productTotal,
                $this->context->cookie
            );
            return $PayrightCalculations;
        } else {
            return 0;
        }
    }

    public function getSessionValue()
    {
        $ConfigValues = $this->getConfigFormValues();

        $PayRightConfig = new Payright\api\PayRightConfig($ConfigValues, null);
        $PayRightApiCall = new Payright\api\Call($PayRightConfig);

       
     
        $payRightAuth =  $PayRightApiCall->payRightAuth($PayRightConfig);

        $payRightAuthObj = json_decode($payRightAuth);

        $this->context->cookie->access_token = $payRightAuthObj->access_token;

        if (isset($payRightAuthObj->access_token)) {
            $configVal = $PayRightApiCall->payRightConfigurationTokenMethod(
                $this->context->cookie,
                $PayRightConfig,
                $payRightAuthObj->access_token
            );
            return $configVal;
        } else {
            return $payRightAuth;
        }
    }

    public function getPayrightInstallments()
    {
        $getSessionValue = $this->getSessionValue();

        if (isset($this->context->cookie->access_token)) {
            $sugarAuthToken= $getSessionValue['auth']->{'auth-token'};
            $configToken = $getSessionValue['configToken'];
            $payrightAccessToken = $this->context->cookie->access_token;
   
            $clientId = $getSessionValue['client_id'];

            $cart = $this->context->cart;

            $allowPlan = $this->getCurrentInstalmentsDisplay($cart->getOrderTotal());

            $PayRightApiCall = new Payright\api\Call();


            $ConfigValues = $this->getConfigFormValues();
            $PayRightConfig = new Payright\api\PayRightConfig($ConfigValues, null);

            $transactionData = array();
            $transactionData['transactionRef'] = $cart->id."_".$clientId.rand();
            $transactionData['clientId'] = $clientId;
            $transactionData['sugarAuthToken'] = $sugarAuthToken;
            $transactionData['configToken'] = $configToken;

            $intializeTransaction = $PayRightApiCall->intializeTransaction(
                $cart->getOrderTotal(),
                $payrightAccessToken,
                $transactionData,
                $PayRightConfig
            );
            $intializeTransactionData = json_decode($intializeTransaction);

            $ecommToken = $intializeTransactionData->ecommToken;

            $moduleShow = true;
            $result = array('moduleShow' => $moduleShow, 'allowPlan' => $allowPlan);
            $this->context->smarty->assign('repayment', $allowPlan['noofrepayments']);
            $this->context->smarty->assign('installment', $allowPlan['LoanAmountPerPayment']);
            $this->context->smarty->assign('redirectUrl', $PayRightConfig->ecomUrl.$ecommToken);
        } else {
            $result = array('moduleShow' => false);
        }

        return $result;
    }

    public function checkPath()
    {
        return $this->_path;
    }
}
