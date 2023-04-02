<?php
/**
 * Placeholder for Payright module
 *
 * @author Payright
 * @copyright 2016-2019 https://www.payright.com.au
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

require_once _PS_MODULE_DIR_ . 'payright/PayrightSDK/api/PayRightConfig.php';
require_once _PS_MODULE_DIR_ . 'payright/PayrightSDK/api/Call.php';
require_once _PS_MODULE_DIR_ . 'payright/PayrightSDK/api/Calculations.php';
require_once _PS_MODULE_DIR_ . 'payright/classes/PayrightOrder.php';

use Payright\api\Call;
use Payright\api\PayRightConfig;
use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

if (!defined('_PS_VERSION_')) {
    exit;
}

ini_set("display_errors", "0");

class Payright extends PaymentModule
{
    protected $html = '';
    protected $postErrors = array();

    public $details;
    public $owner;
    public $address;
    public $extra_mail_vars;

    private $payrightConfigurationValue;
    const CACHE_EXPIRY_IN_SECS = 3600; // 1 hour
    const EXPRESS_CHECKOUT_DISABLED = true;

    public function __construct()
    {
        $this->name = 'payright';
        $this->tab = 'payments_gateways';
        $this->version = '2.0.0';
        $this->ps_versions_compliancy = array('min' => '1.7.8', 'max' => _PS_VERSION_);
        $this->author = 'Payright';
        $this->controllers = array('validation');
        $this->is_eu_compatible = 1;

        $this->currencies = true;
        $this->currencies_mode = 'checkbox';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Payright');
        $this->description = $this->l('A Payment gateway for Payright checkout');
        $this->module_key = '677c9925714d3f3573cc177fb85d9882';

        if (!count(Currency::checkPaymentCurrencies($this->id))) {
            $this->warning = $this->l('No currency has been set for this module.');
        }
    }

    public function install()
    {
        if (!$this->installSQL()) {
            return false;
        }

        $this->registerHook('backOfficeHeader');
        $this->registerHook('header');
        $this->registerHook('displayShoppingCartFooter');
        $this->registerHook('displayNavFullWidth');
        $this->registerHook('actionCartSave');
        $this->registerHook('displayBackOfficeOrderActions');
        $this->registerHook('actionOrderStatusUpdate');

        if (!parent::install() || !$this->registerHook('paymentOptions') || !$this->registerHook('paymentReturn')
            || !$this->registerHook('displayProductPriceBlock')
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayShoppingCartFooter')
            || !$this->registerHook('displayNavFullWidth')
            || !$this->registerHook('actionCartSave')
            || !$this->registerHook('displayBackOfficeOrderActions')
            || !$this->registerHook('actionOrderStatusUpdate')
            || !Configuration::updateValue('PAYRIGHT_LIVE_MODE', '')
            || !Configuration::updateValue('PAYRIGHT_REGION', '')
            || !Configuration::updateValue('CATEGORYPAGE_PAYRIGHTINSTALLMENTS', '')
            || !Configuration::updateValue('INFOMODAL_TEMPLATE', '')
            || !Configuration::updateValue('PS_PAYRIGHT_CUSTOMCSS', '')
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
            $this->getExternalPaymentOption(),
        );

        $ConfigValues = $this->getConfigFormValues();
        $minAmount = $ConfigValues['PS_PAYRIGHT_MINAMOUNT'];
        $orderTotal = $this->context->cart->getOrderTotal();

        if (!($orderTotal >= $minAmount)) {
            return;
        }


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
            ->setLogo(Media::getMediaPath(_PS_MODULE_DIR_ . $this->name . '/views/img/payment.jpg'));

        return $offlineOption;
    }

    public function getExternalPaymentOption()
    {
        $ConfigValues = $this->getConfigFormValues();
        $PayRightConfig = new Payright\api\PayRightConfig($ConfigValues, null);
    
        $PayRightCalculations = new Payright\api\Calculations();

        $orderTotal = $this->context->cart->getOrderTotal();
        $getPayrightConfigurationValue = $this->getPayrightConfigurationValue();

        $PayrightCalculations = $PayRightCalculations->calculateSingleProductInstallment(
            $getPayrightConfigurationValue,
            $orderTotal
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
            ->setAdditionalInformation(
                $this->context->smarty->fetch('module:payright/views/templates/front/payment_infos.tpl')
            )
            ->setLogo(Media::getMediaPath(_PS_MODULE_DIR_ . $this->name . '/views/img/payrightpayment.png'));

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
            ->setLogo(Media::getMediaPath(_PS_MODULE_DIR_ . $this->name . '/views/img/payment.jpg'));

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
            ->setLogo(Media::getMediaPath(_PS_MODULE_DIR_ . $this->name . '/views/img/payment.jpg'));

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
            $years[] = date('Y', strtotime('+' . $i . ' years'));
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
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
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
        $regionOptions = array(
            array(
                'id_option' => 1, // The value of the 'value' attribute of the <option> tag.
                'name' => 'AU', // The value of the text content of the  <option> tag.
            ),
            array(
                'id_option' => 2,
                'name' => 'NZ',
            ),
        );
        $productPageOptions = array(
            array(
                'id_option' => 1, // The value of the 'value' attribute of the <option> tag.
                'name' => 'Yes', // The value of the text content of the  <option> tag.
            ),
            array(
                'id_option' => 2,
                'name' => 'No',
            ),
        );

        $categoryPageOptions = array(
            array(
                'id_option' => 1, // The value of the 'value' attribute of the <option> tag.
                'name' => 'Yes', // The value of the text content of the  <option> tag.
            ),
            array(
                'id_option' => 2,
                'name' => 'No',
            ),
        );

        $relatedProductsOptions = array(
            array(
                'id_option' => 1, // The value of the 'value' attribute of the <option> tag.
                'name' => 'Yes', // The value of the text content of the  <option> tag.
            ),
            array(
                'id_option' => 2,
                'name' => 'No',
            ),
        );

        $frontPageOptions = array(
            array(
                'id_option' => 1, // The value of the 'value' attribute of the <option> tag.
                'name' => 'Yes', // The value of the text content of the  <option> tag.
            ),
            array(
                'id_option' => 2,
                'name' => 'No',
            ),
        );

        // Temporarily disabling express checkout until a better more performant solution is found
        /*
        $cartPageOptions = array(
            array(
                'id_option' => 1, // The value of the 'value' attribute of the <option> tag.
                'name' => 'Yes', // The value of the text content of the  <option> tag.
            ),
            array(
                'id_option' => 2,
                'name' => 'No',
            ),
        );*/

        $modalOptions = array(
            array(
                'id_option' => 1, // The value of the 'value' attribute of the <option> tag.
                'name' => 'Modal 1', // The value of the text content of the  <option> tag.
            ),
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
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('REGION'),
                        'name' => 'PAYRIGHT_REGION',
                        'desc' => $this->l('Region of API'),
                        'required' => true,
                        'options' => array(
                            'query' => $regionOptions,
                            'id' => 'id_option',
                            'name' => 'name',
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('ACCESS TOKEN'),
                        'name' => 'PS_PAYRIGHT_APIKEY',
                        'col' => '3',
                        'required' => true,
                        'desc' => $this->l('Please enter your Access Token'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Minimum Amount ($)'),
                        'name' => 'PS_PAYRIGHT_MINAMOUNT',
                        'col' => '3',
                        'required' => false,
                        'desc' => $this->l('Please enter the minimum sale amount in order for instalments '
                            . 'to be displayed'),
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
                            'name' => 'name',
                        ),
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
                            'name' => 'name',
                        ),
                    ),
                    // Temporarily disabling express checkout until a better more performant solution is found
                    /*array(
                        'type' => 'select',
                        'label' => $this->l('Cart Page'),
                        'desc' => $this->l('Show Payright Installments information on cart page'),
                        'name' => 'CARTPAGE_PAYRIGHTINSTALLMENTS',
                        'required' => true,
                        'options' => array(
                            'query' => $cartPageOptions,
                            'id' => 'id_option',
                            'name' => 'name',
                        ),
                    ),*/

                    array(
                        'type' => 'select',
                        'label' => $this->l('Front Page'),
                        'desc' => $this->l('Show Payright Installments information on front page'),
                        'name' => 'FRONTPAGE_PAYRIGHTINSTALLMENTS',
                        'required' => true,
                        'options' => array(
                            'query' => $frontPageOptions,
                            'id' => 'id_option',
                            'name' => 'name',
                        ),
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
                            'name' => 'name',
                        ),
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
                            'name' => 'name',
                        ),
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Custom CSS'),
                        'name' => 'PS_PAYRIGHT_CUSTOMCSS',
                        'col' => '5',
                        'rows' => '5',
                        'required' => false,
                        'desc' => $this->l('Custom css can be added here for the module'),
                    ),
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
            'PAYRIGHT_REGION' => Configuration::get('PAYRIGHT_REGION', true),
            'PS_PAYRIGHT_APIKEY' => Configuration::get('PS_PAYRIGHT_APIKEY', null),
            'PRODUCTPAGE_PAYRIGHTINSTALLMENTS' => Configuration::get('PRODUCTPAGE_PAYRIGHTINSTALLMENTS', null),
            'CATEGORYPAGE_PAYRIGHTINSTALLMENTS' => Configuration::get('CATEGORYPAGE_PAYRIGHTINSTALLMENTS', null),
            'CARTPAGE_PAYRIGHTINSTALLMENTS' => Configuration::get('CARTPAGE_PAYRIGHTINSTALLMENTS', null),
            'FRONTPAGE_PAYRIGHTINSTALLMENTS' => Configuration::get('FRONTPAGE_PAYRIGHTINSTALLMENTS', null),
            'RELATEDPRODUCTS_PAYRIGHTINSTALLMENTS' => Configuration::get('RELATEDPRODUCTS_PAYRIGHTINSTALLMENTS', null),
            'INFOMODAL_TEMPLATE' => Configuration::get('INFOMODAL_TEMPLATE', null),
            'PS_PAYRIGHT_MINAMOUNT' => Configuration::get('PS_PAYRIGHT_MINAMOUNT', null, null, null, 1),
            'PS_PAYRIGHT_CUSTOMCSS' => Configuration::get('PS_PAYRIGHT_CUSTOMCSS', null)

        );
    }

    /**
     * Retrieves the cached PayRight configuration response. This response contains the merchant rates
     * which will be used for calculations of instalments
     *
     * @return array | false
     */
    protected function getConfigCacheValues()
    {
        $configCache = Configuration::get('PAYRIGHT_CONFIG_CACHE', null, null, null, '');
        $configLastUpdated = Configuration::get('PAYRIGHT_CONFIG_LAST_UPDATED', null, null, null, '');

        if ($configCache !== '' && $configLastUpdated !== '') {
            return array(
                'PAYRIGHT_CONFIG_CACHE' => $configCache,
                'PAYRIGHT_CONFIG_LAST_UPDATED' => $configLastUpdated,
            );
        }

        return false;
    }

    /***
     * Clears the config cache values
     *
     * @return void
     */
    protected function flushConfigCacheValues()
    {
        Configuration::updateValue('PAYRIGHT_CONFIG_CACHE', '');
        Configuration::updateValue('PAYRIGHT_CONFIG_LAST_UPDATED', '');
    }

    /***
     * @param int $lastUpdated Unix timestamp of when the config cache was last updated
     * @return bool
     */
    protected function isConfigCacheExpired($lastUpdated)
    {
        return time() > $lastUpdated + self::CACHE_EXPIRY_IN_SECS;
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.s
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    public function hookActionCartSave()
    {
        // We want this hook to execute only on the cart page, not the checkout/order page
        if (Tools::getValue('controller') === 'order') {
            return;
        }

        // Temporarily disable express checkout link until a more performant solution is found
        if (self::EXPRESS_CHECKOUT_DISABLED === true) {
            return;
        }

        $cartTotal = 0;

        $sugarAuthToken = '';

        $ConfigValues = $this->getConfigFormValues();
        $cartInstalments = $ConfigValues['CARTPAGE_PAYRIGHTINSTALLMENTS'];
        $minAmount = $ConfigValues['PS_PAYRIGHT_MINAMOUNT'];
        $apiKey = $ConfigValues['PS_PAYRIGHT_APIKEY'];

        $cart = $this->context->cart;

        if (isset($cart)) {
            if ($cart->getOrderTotal() != null) {
                $cartTotal = $cart->getOrderTotal();
            }
        }

        if (isset($this->context->cookie->access_token) && $cartTotal > 0 &&  $cartTotal >= $minAmount) {
            $PayRightConfig = new Payright\api\PayRightConfig($ConfigValues, null);
            $PayRightApiCall = new Payright\api\Call($PayRightConfig);

            $configTranscationVal = $PayRightApiCall->payRightTranscationConfigurationTokenMethod(
                $PayRightConfig
            );

            $sugarAuthToken = $configTranscationVal['auth']->{'auth-token'};
            $configToken = $configTranscationVal['configToken'];
            $payrightAccessToken = $this->context->cookie->access_token;

            $clientId = $configTranscationVal['client_id'];

            $allowPlan = $this->getCurrentInstalmentsDisplay($cartTotal);

            $transactionData = array();
            $transactionData['transactionRef'] = $cart->id . "_" . $clientId . rand();
            $transactionData['clientId'] = $clientId;
            $transactionData['sugarAuthToken'] = $sugarAuthToken;
            $transactionData['configToken'] = $configToken;
            $baseUrl = Context::getContext()->shop->getBaseURL(true);

            $initialiseTransaction = $PayRightApiCall->initialiseTransaction(
                $cartTotal,
                $transactionData,
                $PayRightConfig,
                $baseUrl
            );
            $initialiseTransactionData = json_decode($initialiseTransaction);

            $ecommToken = $initialiseTransactionData->ecommToken;

            $moduleShow = true;

            if (isset($allowPlan['noofrepayments'])) {
                $this->context->smarty->assign('repayment', $allowPlan['noofrepayments']);
                $this->context->smarty->assign('installment', $allowPlan['LoanAmountPerPayment']);
            }

            $this->context->smarty->assign('redirectUrl', $PayRightConfig->ecomUrl . $ecommToken);
        } else {
            $moduleShow = false;
        }

        if ($moduleShow == 1 && $allowPlan != 'exceed_amount' && $cartInstalments == 1 && $cartTotal >= $minAmount) {
            return $this->context->smarty->fetch("module:payright/views/templates/hook/cart_payright.tpl");
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookDisplayHeader()
    {
        $getPayrightConfigurationValue = $this->getPayrightConfigurationValue();

        if ($getPayrightConfigurationValue == "error") {
            $this->context->cookie->error = $getPayrightConfigurationValue;
        } else {
            $this->context->cookie->error = '';
        }
      
        $this->context->smarty->assign("payright_base_url", Context::getContext()->shop->getBaseURL(true));
    
        $ConfigValues = $this->getConfigFormValues();
        $payrightcss = $ConfigValues['PS_PAYRIGHT_CUSTOMCSS'];

        if ($payrightcss !== null) {
            $this->smarty->assign('payrightcss', $payrightcss);
            return $this->display(__FILE__, 'payrightcustom_css.tpl');
        }
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
        // Temporarily disable express checkout link until a more performant solution is found
        if (self::EXPRESS_CHECKOUT_DISABLED === true) {
            return;
        }

        $installmentResult = $this->getPayrightInstallments();
        $ConfigValues = $this->getConfigFormValues();
        $cartInstalments = $ConfigValues['CARTPAGE_PAYRIGHTINSTALLMENTS'];

        if ($cartInstalments == 1
            && $installmentResult['moduleShow'] == 1
            && $installmentResult['allowPlan'] != 'exceed_amount') {
            return $this->context->smarty->fetch("module:payright/views/templates/hook/cart_payright.tpl");
        }
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            if ($key === 'PAYRIGHT_ACCOUNT_PASSWORD' && Tools::getValue($key) === '') {
                continue;
            }

            Configuration::updateValue($key, Tools::getValue($key));
        }

        $this->flushConfigCacheValues();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */

        if (((bool) Tools::isSubmit('submitPayrightModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);
        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');
        return $output . $this->renderForm();
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

        if ($this->context->cookie->error == "error") {
            return;
        }

        // This resets payright_instalment_breakdown to a default value. Necessary when this hook is being run in a
        // loop i.e. multiple products listed in one page. Otherwise the value of payright_instalment_breakdown could
        // still hold the breakdown for the previous product if the value is not set/cleared.
        $this->context->smarty->assign("payright_instalment_breakdown", 0);       

        if ($current_controller == 'category' && $params["type"] == 'unit_price' && $categoryInstalments == 1) {
            $payRightInstallmentBreakDown = $this->getCurrentInstalmentsDisplay($params["product"]["price_amount"]);

            if ($payRightInstallmentBreakDown != false) {
                $this->context->smarty->assign("payright_instalment_breakdown", $payRightInstallmentBreakDown);
            }
            return $this->context->smarty->fetch("module:payright/views/templates/front/product_thumbnail.tpl");
        }
        if ($current_controller == "product" && $params["type"] == "after_price" && $productInstallments == 1) {
            $payRightInstallmentBreakDown = $this->getCurrentInstalmentsDisplay($params["product"]["price_amount"]);
            // var_dump($);
            if ($payRightInstallmentBreakDown != false) {
                $this->context->smarty->assign("payright_instalment_breakdown", $payRightInstallmentBreakDown);
            }
            $this->context->smarty->assign("templateValue", $templateValue);
            return $this->context->smarty->fetch("module:payright/views/templates/front/product_page.tpl");
        }

        if ($current_controller == 'product' && $params["type"] == 'unit_price' && $relatedProductsInstalments == 1) {
            $payRightInstallmentBreakDown = $this->getCurrentInstalmentsDisplay($params["product"]["price_amount"]);
            if ($payRightInstallmentBreakDown != false) {
                $this->context->smarty->assign("payright_instalment_breakdown", $payRightInstallmentBreakDown);
            }
            return $this->context->smarty->fetch("module:payright/views/templates/front/product_thumbnail.tpl");
        }

        if ($current_controller == "index" && $params["type"] == "unit_price" && $frontpageInstalments == 1) {
            $payRightInstallmentBreakDown = $this->getCurrentInstalmentsDisplay($params["product"]["price_amount"]);

            if ($payRightInstallmentBreakDown != false) {
                $this->context->smarty->assign("payright_instalment_breakdown", $payRightInstallmentBreakDown);
            }
            $this->context->smarty->assign("templateValue", $templateValue);
            return $this->context->smarty->fetch("module:payright/views/templates/front/product_thumbnail.tpl");
        }

        if ($current_controller == "orderconfirmation" &&
            $params["type"] == "unit_price" && $frontpageInstalments == 1) {
            if (isset($params["product"]["price_amount"])) {
                $payRightInstallmentBreakDown = $this->getCurrentInstalmentsDisplay($params["product"]["price_amount"]);

                if ($payRightInstallmentBreakDown != false) {
                    $this->context->smarty->assign("payright_instalment_breakdown", $payRightInstallmentBreakDown);
                }
                $this->context->smarty->assign("templateValue", $templateValue);
                return $this->context->smarty->fetch("module:payright/views/templates/front/product_thumbnail.tpl");
            }
        }
    }

    public function getCurrentInstalmentsDisplay($productTotal)
    {
        $getPayrightConfigurationValue = $this->getPayrightConfigurationValue();

        $rateUnserialized = $getPayrightConfigurationValue['rates'];
        $ConfigValues = $this->getConfigFormValues();
        $minAmount = $ConfigValues['PS_PAYRIGHT_MINAMOUNT'];
        //  $rateUnserialized = ($rateCard);
        if (!empty($rateUnserialized) && ($productTotal >= $minAmount)) {
            $PayRightCalculations = new Payright\api\Calculations();
            $PayrightCalculations = $PayRightCalculations->calculateSingleProductInstallment(
                $getPayrightConfigurationValue,
                $productTotal
            );

            return $PayrightCalculations;
        } else {
            return 0;
        }
    }

    public function getPayrightConfigurationValue()
    {
        // Return the configuration if it has already been previously loaded
        if ($this->payrightConfigurationValue !== null && $this->payrightConfigurationValue !== 'error') {
            return $this->payrightConfigurationValue;
        }

        // Retrieve the cached configuration from the DB
        $payrightConfigurationCache = $this->getConfigCacheValues();
        if ($payrightConfigurationCache !== false &&
            !$this->isConfigCacheExpired($payrightConfigurationCache['PAYRIGHT_CONFIG_LAST_UPDATED'])
        ) {
            $this->payrightConfigurationValue = (array) json_decode(
                $payrightConfigurationCache['PAYRIGHT_CONFIG_CACHE']
            );
            return $this->payrightConfigurationValue;
        }

        // Otherwise, retrieve the configuration from PayRight API
        $ConfigValues = $this->getConfigFormValues();

        $PayRightConfig = new Payright\api\PayRightConfig($ConfigValues, null);
        $PayRightApiCall = new Payright\api\Call($PayRightConfig);

        $configVal = $PayRightApiCall->payRightConfigurationTokenMethod(
            $PayRightConfig
        );

        $this->payrightConfigurationValue = $configVal;

        // Save the configuration to local DB
        Configuration::updateValue('PAYRIGHT_CONFIG_CACHE', json_encode($this->payrightConfigurationValue));
        Configuration::updateValue('PAYRIGHT_CONFIG_LAST_UPDATED', time());

        return $this->payrightConfigurationValue;
    }

    public function getPayrightInstallments()
    {
        $ConfigValues = $this->getConfigFormValues();
        $PayRightConfig = new Payright\api\PayRightConfig($ConfigValues, null);
        $PayRightApiCall = new Payright\api\Call($PayRightConfig);

        $configTranscationVal = $PayRightApiCall->payRightTranscationConfigurationTokenMethod(
            $PayRightConfig
        );

        $sugarAuthToken = $configTranscationVal['auth']->{'auth-token'};
        $configToken = $configTranscationVal['configToken'];
        $payrightAccessToken = $this->context->cookie->access_token;

        $clientId = $configTranscationVal['client_id'];

        $cart = $this->context->cart;

        $allowPlan = $this->getCurrentInstalmentsDisplay($cart->getOrderTotal());

        $PayRightApiCall = new Payright\api\Call();

        $ConfigValues = $this->getConfigFormValues();
        $PayRightConfig = new Payright\api\PayRightConfig($ConfigValues, null);

        $transactionData = array();
        $transactionData['transactionRef'] = $cart->id . "_" . $clientId . rand();
        $transactionData['clientId'] = $clientId;
        $transactionData['sugarAuthToken'] = $sugarAuthToken;
        $transactionData['configToken'] = $configToken;
        $baseUrl = Context::getContext()->shop->getBaseURL(true);

        $initialiseTransaction = $PayRightApiCall->initialiseTransaction(
            $cart->getOrderTotal(),
            $transactionData,
            $PayRightConfig,
            $baseUrl
        );

        $moduleShow = true;
        $result = array('moduleShow' => $moduleShow, 'allowPlan' => $allowPlan);

        if (isset($allowPlan['noofrepayments'])) {
            $this->context->smarty->assign('repayment', $allowPlan['noofrepayments']);
            $this->context->smarty->assign('installment', $allowPlan['LoanAmountPerPayment']);
        }

        // Commented out for now since express checkout has been disabled
        // $this->context->smarty->assign('redirectUrl', $PayRightConfig->ecomUrl . $ecommToken);

        return $result;
    }

    public function checkPath()
    {
        return $this->_path;
    }

    /**
     * Install DataBase table
     * @return boolean if install was successfull
     */
    public function installSQL()
    {
        // $sql = array();

        $sql = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "payright_order` (
              `id_payright_order` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `id_order` INT(11),
              `id_cart` INT(11),
              `plan_id` VARCHAR(55),
              `plan_name` VARCHAR(55),
              `order_reference` VARCHAR(255),
              `payment_method` VARCHAR(255),
              `payment_status` VARCHAR(255),
              `date_add` DATETIME,
              `date_upd` DATETIME
        ) ENGINE = " . _MYSQL_ENGINE_;

        if (!DB::getInstance()->execute($sql)) {
            return false;
        }

        return true;
    }

    /**
     * Uninstall DataBase table
     * @return boolean if install was successfull
     */
    private function uninstallSQL()
    {
        // $sql   = array();
        $sql = "DROP TABLE IF EXISTS `" . _DB_PREFIX_ . "payright_order`";

        if (!DB::getInstance()->execute($sql)) {
            return false;
        }

        return true;
    }
    public function uninstall()
    {
        // Uninstall DataBase
        if (!$this->uninstallSQL()) {
            return false;
        }

        // Uninstall default
        if (!parent::uninstall()) {
            return false;
        }
        return true;
    }

    /**
    * for activating plans
     */
    public function hookActionOrderStatusUpdate($params)
    {
        $orderId = $params['id_order']; // order ID
        $checkoutId = PayrightOrder::getPlanByOrderId($orderId);

        if ($params['newOrderStatus']->id == Configuration::get('PS_OS_SHIPPING')) {
            $PayRightApiCall = new Payright\api\Call();

            $ConfigValues = $this->getConfigFormValues();
            $PayRightConfig = new Payright\api\PayRightConfig($ConfigValues, null);

            if (isset($checkoutId)) {
                $status = $PayRightApiCall->planStatusActivate($PayRightConfig, $checkoutId);

                if (array_key_exists('error', $status['data'])) {
                    $planResult = $status['data']['error_message'];
                } else {
                    $planResult = $status['data']['message'];
                }

                PayrightOrder::updatePaymentStatus($planResult, $checkoutId);
            }
        }

    }

    public function hookDisplayBackOfficeOrderActions($params)
    {
        if (isset($params['id_order'])) {
            $id = $params['id_order'];
            $result = PayrightOrder::getPlanStatusByOrderId($id);
        }

        if ($result === 'Active') {
            echo "<br><br><div class='alert alert-success'>Your Plan has been activated Successfully</div>";
        } elseif ($result !== 'Active' && $result !== 0) {
            echo "<br><br><div class='alert alert-warning'>
            " . $result . " Please contact support@payright.com.au</div>";
        } else {
            echo "<br><br><div class='alert alert-warning'>The Plan will not activate until the product
            is shipped</div>";
        }
    }
}
