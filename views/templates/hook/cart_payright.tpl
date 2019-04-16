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

<div id="grid">

<div id="btn2"><a href="{$redirectUrl|escape:'htmlall':'UTF-8'}"><img src="{$payright_base_url|escape:'htmlall':'UTF-8'}modules/payright/views/img/payrightlogo_rgb.png" />Buy Now Pay Later</a></div>
<!-- <div id="btn2"><a href="https://www.payright.com.au/"><img src="http://betacustomerportal.payright.com.au/img/Payright_LOGO_RGB.png"/>Interest Free Payments</a></div> -->

</div>

<style type="text/css">


/*#btn{
border:0px solid white;
    margin: 10px 40px 0 0;
    float: right;
    cursor: pointer;
} */
#btn2{
    border: 1px solid white;
    margin: 10px 40px 0 0;
    float: right;
    background-color: white;
    border-radius: 5px;
    font-size: 12px;
    font-family: 'Quicksand', sans-serif;
    border-right-width: 10px;
    cursor: pointer;

}

img{
border:1px solid white;
border-radius: 5px;
max-height:50px;
background-color:white;
}

#grid{
display: grid;
float: right;

}
a{
	color: #5431FF;
}


</style>