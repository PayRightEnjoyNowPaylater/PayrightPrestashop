<?php

/**
 * Class AfterpayCapture
 *
 * Afterpay PrestaShop Module API Capture class 
 * Utilise Afterpay API V1
 */
class PayrightCapture
{


  public function __construct() 
  {

  }

  public function createCapturePayment($planName) {
  		 $this->_createOrder($planName);
       $return['error']     =   false;

       return $return;
  }


   /**
    * Create Order function
    * Create the PrestaShop Order after a successful Capture
    * @param string $afterpay_order_id
    * since 1.0.0
    */
    private function _createOrder($payrightPlanName) {

        $cart = Context::getContext()->cart;

        $order_status = (int)Configuration::get("PS_OS_PAYMENT");

        $order_total = $cart->getOrderTotal(true, Cart::BOTH);

        $module = Module::getInstanceByName("payright");

        $extra_vars =   array(
                            "planName"    =>  $payrightPlanName
                        );

        $module->validateOrder($cart->id, $order_status, $order_total, "payright", null, $extra_vars, null, false, $cart->secure_key);
        
        $message = "Payright Order Captured Successfully - Order ID: " . $payrightPlanName . "; PrestaShop Cart ID: " . $cart->id;
        PrestaShopLogger::addLog($message, 1, NULL, "Payright", 1);

    }

}