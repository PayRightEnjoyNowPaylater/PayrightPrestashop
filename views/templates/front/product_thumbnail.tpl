<span>
	<p>
{if $payright_instalment_breakdown != 0}
<small>or {$payright_instalment_breakdown['noofrepayments']} {$payright_instalment_breakdown['repaymentfrequency']} Installments of ${$payright_instalment_breakdown['LoanAmountPerPayment']}</small>
<img class="payright-logo" src="{$payright_base_url}modules/payright/images/payrightlogo.png">


{/if}
</p> 
</span>

<!-- overiding prestashop default css -->
<style type="text/css">
	#products .product-description, .featured-products .product-description, .product-accessories .product-description, .product-miniature .product-description {
    position: absolute;
    z-index: 1;
    background: #fff;
    width: 257px;
    bottom: 0;
    height: auto;
}

.payright-logo
{
	height:20px;
}
</style>