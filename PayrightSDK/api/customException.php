<?php
/**
 * Placeholder for Payright Exception
 *
 * @author Payright
 * @copyright 2016-2019 https://www.payright.com.au
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class CustomException extends Exception
{
    public function errorMessage()
    {
        //error message
        $errorMsg = "error";
        return $errorMsg;
    }
}
