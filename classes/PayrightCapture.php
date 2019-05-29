<?php
/**
 * Payright capture class
 *
 * @author Payright
 * @copyright 2016-2019 https://www.payright.com.au
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class PayrightCapture
{
    public function __construct()
    {
    }

    public function createCapturePayment($planName)
    {
        $this->createOrder($planName);

        $return = array();
        $return['error']     =   false;

        return $return;
    }


    /**
     * Create Order function
     * Create the PrestaShop Order after a successful Capture
     * @param string $afterpay_order_id
     * since 1.0.0
     */
    private function createOrder($payrightPlanName)
    {
        $cart = Context::getContext()->cart;

        $order_status = (int)Configuration::get("PS_OS_PAYMENT");

        $order_total = $cart->getOrderTotal(true, Cart::BOTH);

        $module = Module::getInstanceByName("payright");

        $extra_vars =   array(
                            "planName"    =>  $payrightPlanName
                        );

        $module->validateOrder(
            $cart->id,
            $order_status,
            $order_total,
            "payright",
            null,
            $extra_vars,
            null,
            false,
            $cart->secure_key
        );
        
        $message = "Payright Order Captured Successfully - Order ID: " .
        $payrightPlanName . "; PrestaShop Cart ID: " . $cart->id;
        PrestaShopLogger::addLog($message, 1, null, "Payright", 1);
    }
}
