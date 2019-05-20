<?php
/**
 * Payright capture class
 *
 * @author Payright
 * @copyright 2016-2019 https://www.payright.com.au
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

require_once _PS_MODULE_DIR_ . 'payright/classes/PayrightOrder.php';

class PayrightCapture
{
    public function __construct()
    {
    }

    public function createCapturePayment($planName, $planId)
    {
        $this->createOrder($planName, $planId);

        $return          = array();
        $return['error'] = false;

        return $return;
    }

    /**
     * Create Order function
     * Create the PrestaShop Order after a successful Capture
     * @param string $afterpay_order_id
     * since 1.0.0
     */
    private function createOrder($payrightPlanName, $planId)
    {
        $cart = Context::getContext()->cart;

        $order_status = (int) Configuration::get("PS_OS_PAYMENT");

        $order_total = $cart->getOrderTotal(true, Cart::BOTH);

        $module = Module::getInstanceByName("payright");

        $extra_vars = array(
            "planName" => $payrightPlanName,
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

        if (Tools::version_compare(_PS_VERSION_, '1.7.1.0', '>')) {
            $order    = Order::getByCartId($cart->id);
            $id_order = $order->id;
        } else {
            $id_order = Order::getOrderByCartId($cart->id);
            $order    = new Order($id_order);
        }

        $sql = 'UPDATE `' . _DB_PREFIX_ . 'order_payment`
            SET `transaction_id` = "' . $payrightPlanName . '"
            WHERE  `order_reference` = "' . pSQL($order->reference) . '"';
        Db::getInstance()->execute($sql);

        $payright_order                  = new PayrightOrder();
        $payright_order->id_order        = $order->id;
        $payright_order->id_cart         = $cart->id;
        $payright_order->plan_name       = $payrightPlanName;
        $payright_order->plan_id         = $planId;
        $payright_order->order_reference = $order->reference;
        $payright_order->payment_method   = $order->payment;
        $payright_order->payment_status   = " ";
        $payright_order->save();

        $message = "Payright Order Captured Successfully - Order ID: " .
        $payrightPlanName . "; PrestaShop Cart ID: " . $cart->id;
        PrestaShopLogger::addLog($message, 1, null, "Payright", 1);
    }
}
