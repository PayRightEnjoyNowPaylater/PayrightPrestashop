{*
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2019 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<section>
  <h2>{$noofrepayments|escape:'htmlall':'UTF-8'} {$repaymentfrequency|escape:'htmlall':'UTF-8'} installments of ${$LoanAmountPerPayment|escape:'htmlall':'UTF-8'}</h2>
  <p>{l s='Excluding deposit'  mod='payright'}
  	<br>
  	{l s='You will be directed to the PayRight website to complete the application process'  mod='payright'}
  	<br>
  	Once approved you will return to our page
  	<br>
  	See PayRight terms & conditions for further information.
  </p>
</section>

<style type="text/css">
label{
text-align: left;

}
span{
	vertical-align: inherit;
}


</style>