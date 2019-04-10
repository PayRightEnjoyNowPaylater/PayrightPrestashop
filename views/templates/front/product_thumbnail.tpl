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
{block name=head}
   <link href="/modules/payright/views/css/sample.css" rel="stylesheet" type="text/css"/>

{/block}
<span>
	<p>
{if $payright_instalment_breakdown != 0}
<small>or {$payright_instalment_breakdown['noofrepayments']|escape:'htmlall':'UTF-8'} {$payright_instalment_breakdown['repaymentfrequency']|escape:'htmlall':'UTF-8'} Instalments of ${$payright_instalment_breakdown['LoanAmountPerPayment']|escape:'htmlall':'UTF-8'}</small>
<img class="payright-logo" src="{$payright_base_url|escape:'htmlall':'UTF-8'}modules/payright/views/img/payrightlogo.png">


{/if}
</p> 
</span>

<!-- overiding prestashop default css -->
<style type="text/css">
/*#products .product-description, .featured-products .product-description, .product-accessories .product-description, .product-miniature .product-description {
 
    height: auto;
}
#products .highlighted-informations, .featured-products .highlighted-informations, .product-accessories .highlighted-informations, .product-miniature .highlighted-informations {
    
    height: 6.125rem;
   
}

.payright-logo
{
	height:20px;
}*/
</style>