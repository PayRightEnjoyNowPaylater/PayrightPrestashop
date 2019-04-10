htn{*
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
   <link href="/modules/payright/views/css/main1.css" rel="stylesheet" type="text/css"/>
   <link href="/modules/payright/views/css/main2.css" rel="stylesheet" type="text/css"/>

{/block}

<p>
<small>or {$payright_instalment_breakdown['noofrepayments']|escape:'htmlall':'UTF-8'} {$payright_instalment_breakdown['repaymentfrequency']|escape:'htmlall':'UTF-8'} Instalments of ${$payright_instalment_breakdown['LoanAmountPerPayment']|escape:'htmlall':'UTF-8'} </small>


<img class="payright-logo2" id=prlogo2 src="{$payright_base_url|escape:'htmlall':'UTF-8'}modules/payright/views/img/payrightlogo.png" />
{if $templateValue == '1'}
<a style='color:#275f95' id="opener"  class="payright-modal-popup-trigger" > Info</a> </p>
{else}
<a style='color:#275f95' id="opener"  class="payright-modal-popup-trigger2" > Info</a> </p>
{/if}
<!-- The Modal -->
<div id='myModal' class='payrightmodal'>

  <!-- Modal content -->
  <div class='modal-content'>
    <div class='modal-body'>
    <div id='close'>X</div>
     <div class="payRight_container" id="PayrightHowitWorksmodalPopup2">
    <header>
        <div class="payRight_columns">
            <div class="insideColumns">
            </div>
            <div class="insideColumns payright_is-3 imgPayRight">
                <img class="payRightimg" src="https://betaonlineapi.payright.com.au/images/PayRight_FC_Logo.png" alt="PayRight logo" />
            </div>
            <div class="insideColumns payRight_headerLast">
            </div>
        </div>
        <div class="payRight_columns">
            <div class="insideColumns">
                    <h4 class="payRightH4 op2">Buy now and pay for your purchase over time with Payright</h4>
            </div>
        </div>


    </header>

    <article>
        <div class="payRight_columns payRight_blueStrip2 payRight_blueStripTop2">
            <div class="insideColumns payRight_topleft2">
                <img src="https://betaonlineapi.payright.com.au/images/icon-payright-tick.png" alt="" />
                <p class="payRightp">Select PayRight as your<br/>preferred payment option</p>
            </div>
            <div class="insideColumns">
                <img src="https://betaonlineapi.payright.com.au/images/icon-computer.png" alt="" />
                <p class="payRightp">Enter your contact details to sign up<br/>or sign in to your Payright account</p>
            </div>
        </div>
        <div class="payRight_columns payRight_blueStrip2 payRight_blueStripBottom2">
        
            <div class="insideColumns">
                <img src="https://betaonlineapi.payright.com.au/images/icon-delivery.png" alt="PayRight logo"  alt="test" }"/>
                <p class="payRightp">Once approved, your purchase<br/>will be scheduled for dispatch</p>
            </div>
            <div class="insideColumns payRight_bottomright2">
                <img src="https://betaonlineapi.payright.com.au/images/icon-calendar.png" alt="" />
                <p class="payRightp">Pay over time with<br/>Interest Free payments</p>
            </div>
        </div>
    </article>

    <article>
        <div class="payRight_columns info2">
            <div class="insideColumns">
                <h5 class="payRightH5">Important Information:</h5>
                <p class="payRightp">We want to ensure you have a positive payment experience and have made the checkout process simple. We take responsible lending seriously so credit is only extended to approved customers. Please ensure you read the terms and conditions for further information and note that Payright is not available on all purchases.</p>
            </div>
        </div>
    </article>

</div>
<!--begining of modal-->
<div id="PayrightHowitWorksmodalPopup" class="payRight_container">
    <div class="payRight_container">
  <header>
    <div class="payRight_columns">
      <div class="insideColumns imgPayRight">
        <img class="payRightimg" src="https://betaonlineapi.payright.com.au/images/PayRight_FC_Logo.png" alt="PayRight logo" />
      </div>
      <div class="insideColumns payRight_is-three-quarters">
        <h1 class="payRightH1" id="merchantname">Merchant<br/>HAS PARTNERED WITH</h1>
      </div>
      
    </div>
  </header>

  <article>
    <div class="payRight_columns payRight_blueStrip">
      <div class="insideColumns">
        <h2 class="payRightH2works">How PayRight works:</h2>

        <div class="PointsInBlueStrip">
        
          <div class="payRight_columns">
            <div class="insideColumns payRight_is-5 payRight_is-offset-1">
              <span class="numC">1</span>
              <p class="payRightp"> Select your<br/>purchase online</p>
            </div>
            <div class="insideColumns payRight_is-5">
              <span class="numC">2</span>
              <p class="payRightp"> Select PayRight as your<br/>preferred payment option</p>
            </div>
          </div>
      
          <div class="payRight_columns">
            <div class="insideColumns payRight_is-5 payRight_is-offset-1">
              <span class="numC">3</span>
              <p class="payRightp">Enter your contact details to sign up<br/>or sign in to your PayRight account</p>
            </div>
            <div class="insideColumns payRight_is-5">
              <span class="numC">4</span>
              <p class="payRightp">Once approved, (insert merchant name)<br/>will dispatch your purchase</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>

  <article>
    <div class="payRight_columns">
      <div class="insideColumns">
        <h2 class="payRightH2why">Why PayRight?</h2>
        <h4 class="payRightH4">Buy now and pay for your purchase over time with PayRight</h4>
        <div class="payRight_columns payRight_points">
          <div class="insideColumns">
            <p class="payRightpwhy">Easy instalments<br/>that are always<br/>interest free</p>
          </div>
          <div class="insideColumns pointsCenter">
            <p class="payRightpwhy" >Get what you<br/>want today and<br/>pay over time</p>
          </div>
          <div class="insideColumns">
            <p class="payRightpwhy" >Quick, Simple &<br/>Secure sign up and<br/>check out process</p>
          </div>
        </div>

      </div>
    </div>
  </article>

  <article>
    <div class="payRight_columns info">
      <div class="insideColumns">
        <h5 class="payRightH5">Important Information:</h5>
        <p class="payRightpimportant" >We want to ensure you have a positive payment experience and have made the checkout process simple. We take responsible lending seriously so credit is only extended to approved customers. Please ensure you read the terms and conditions for further information and note that PayRight is not available on all purchases.</p>
      </div>
    </div>
  </article>

  <article>
    <div class="payRight_columns">
      <div class="insideColumns payRight_is-one-third payRight_is-offset-one-third">
        <button class="payrightbutton"><a href="https://www.payright.com.au/" id="tellmemore">Tell me more</a></button>
      </div>
    </div>
    
  </article>
</div>
</div>
<!--end of modal -->



    </div>
  </div>

</div>

{block name=head}
<script type="text/javascript" src="/modules/payright/views/js/modals.js" ></script>
{/block}

