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
   <link href="/modules/payright/views/css/main1.css" rel="stylesheet" type="text/css"/>
   <link href="/modules/payright/views/css/main2.css" rel="stylesheet" type="text/css"/>
   <link href="/modules/payright/views/css/payright-modal.css" rel="stylesheet" type="text/css"/>

{/block}

<p>
{if $payright_instalment_breakdown != 0}
<small>or {$payright_instalment_breakdown['noofrepayments']|escape:'htmlall':'UTF-8'} {$payright_instalment_breakdown['repaymentfrequency']|escape:'htmlall':'UTF-8'} Instalments of ${$payright_instalment_breakdown['LoanAmountPerPayment']|escape:'htmlall':'UTF-8'} </small>

<span class="payright-logo-product" ><img  src="{$urls.base_url|escape:'htmlall':'UTF-8'}modules/payright/views/img/payrightlogo_rgb.png" /></span>
{if $templateValue == '1'}
<a style='color:#275f95; cursor:pointer;' id="opener"  class="payright-modal-popup-trigger2" > Info</a> </p>
{else}
<a style='color:#275f95; cursor:pointer;' id="opener"  class="payright-modal-popup-trigger" > Info</a> </p>
{/if}

{/if}
<!-- The Modal -->
<div id='myModal' class='payrightmodal'>

  <!-- Modal content -->
  <!-- <div class='modal-content'>
    <div class='modal-body'> -->
    <div id='close'>X</div>
    
<!--begining of modal-->


                    <!-- ***** START MODAL ***** -->
                   <!--  <div id="PayrightHowitWorksmodalPopup" class="payRight_container">
                        <header>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="280px" height="60px" viewBox="0 0 280 60"><defs><clipPath id="clip-path"><path d="M58.0918,13.71A15.48,15.48,0,0,0,44.3883.6493q-.39-.04-.7855-.06T42.81.57a15.475,15.475,0,0,0-15.46,15.4593v27.73a9.7139,9.7139,0,0,1-2.68,6.7042,9.3623,9.3623,0,0,1-3.2456,2.223c-.0842.0343-.1684.0683-.2536.1-.1715.064-.3452.1219-.52.1756a9.1072,9.1072,0,0,1-1.6075.34q-.4568.0537-.9226.0617c-.0537.0008-.1076.0015-.1612.0015A9.405,9.405,0,0,1,8.6156,44.906q-.0453-.4614-.0454-.9312a9.4029,9.4029,0,0,1,8.4309-9.3412c.1108-.0113.2269-.02.35-.0277l.0154-.0006c.119-.0067.2438-.0118.3775-.0145h.319a2.9378,2.9378,0,0,0,2.9344-2.934v-.203a2.9372,2.9372,0,0,0-2.9338-2.934l-.0442-.0024h0c-.0569-.0036-.1152-.002-.1723,0h0c-.0313.0012-.0624.002-.0939.0024h-.0173l-.0143.0008A15.4752,15.4752,0,0,0,2.5,43.9744v0a15.4809,15.4809,0,0,0,13.8811,15.38q.39.04.7855.06t.7931.02A15.4749,15.4749,0,0,0,33.42,43.9748V16.245A9.7121,9.7121,0,0,1,36.1,9.5408a9.3612,9.3612,0,0,1,3.2456-2.2231c.0842-.0343.1684-.0683.2536-.1.1715-.0641.3452-.122.52-.1756q.2382-.0729.48-.1326c.1867-.0462.3748-.0872.5647-.1216.1863-.0336.3734-.0636.5624-.0858q.4567-.0536.9226-.0615c.0537-.0008.1076-.0016.1612-.0016a9.4119,9.4119,0,0,1,9.208,7.5463,9.4026,9.4026,0,0,1-8.25,11.1849c-.1108.0112-.2269.02-.35.0276l-.0154.0006c-.119.0067-.2438.0118-.3775.0146h-.319a2.9376,2.9376,0,0,0-2.9344,2.934v.203a2.9372,2.9372,0,0,0,2.9338,2.934l.0442.0024h0c.0563.0031.1146.0016.1723,0h0c.0313-.0012.0624-.002.0939-.0024h.0173l.0143-.0008a15.4752,15.4752,0,0,0,15.221-15.4538v0A15.4519,15.4519,0,0,0,58.0918,13.71Z" style="fill:none"/></clipPath><clipPath id="clip-path-2"><rect x="-2.5" y="-4.4307" width="65.7695" height="68.8652" style="fill:none"/></clipPath></defs><path d="M192.8712,10.5382a3.2211,3.2211,0,1,1-3.221-3.2211A3.2211,3.2211,0,0,1,192.8712,10.5382ZM171.63,20.6933a2.9992,2.9992,0,0,0-2.8608-2.5,2.9562,2.9562,0,0,0-3.0178,2.2442,5.7308,5.7308,0,0,0-.1407,1.3433q-.0134,8.6786-.0059,17.3569c0,.8912-.01,1.7825.0034,2.6735a3.5847,3.5847,0,0,0,.5128,2.0432,3.0479,3.0479,0,0,0,3.4131,1.1908,2.7261,2.7261,0,0,0,2.0913-2.2884,5.9141,5.9141,0,0,0,.0742-.9395c.0311-3.8648.0278-7.73.0981-11.5943a9.8906,9.8906,0,0,1,.8915-4.0282,4.4644,4.4644,0,0,1,3.2483-2.6447,7.7872,7.7872,0,0,1,2.86-.0517,3.0067,3.0067,0,0,0,1.625-.0673,2.5641,2.5641,0,0,0,1.6939-2.1933,2.4178,2.4178,0,0,0-1.01-2.295,3.6722,3.6722,0,0,0-1.6541-.6863,7.0675,7.0675,0,0,0-3.3588.3713,8.892,8.892,0,0,0-3.8656,2.4855c-.1462.1551-.2929.31-.4818.5094C171.701,21.255,171.6783,20.9719,171.63,20.6933ZM99.5,31.6524c0,8.0647-5.9423,14.4317-13.6432,14.4317A11.0746,11.0746,0,0,1,77.61,42.567a.66.66,0,0,0-1.1521.4852l0,12.43a3.0323,3.0323,0,0,1-3.0322,3.0323h0a3.0323,3.0323,0,0,1-3.0323-3.0322l.0005-34.5087a2.7827,2.7827,0,0,1,2.782-2.7826h0a2.7825,2.7825,0,0,1,2.7816,2.6918l.0151.4618c.0606.5457.667.6669,1.0914.182a10.752,10.752,0,0,1,8.7922-4.2446C93.5574,17.2818,99.5,23.5879,99.5,31.6524Zm-6.0635,0a8.4894,8.4894,0,1,0-8.4893,8.55A8.4884,8.4884,0,0,0,93.4362,31.6524Zm39.0026-10.6508V42.3678A2.8118,2.8118,0,0,1,129.627,45.18h0a2.8118,2.8118,0,0,1-2.81-2.7184l-.0167-.5006c-.0606-.5456-.667-.6668-1.0308-.1212a10.7523,10.7523,0,0,1-8.7924,4.2447c-7.7007,0-13.6432-6.367-13.6432-14.4317s5.9425-14.3706,13.6432-14.3706a10.5906,10.5906,0,0,1,8.7924,4.2446c.3638.4849.97.3637,1.0308-.182l.0149-.4382a2.8128,2.8128,0,0,1,2.8111-2.7174h0A2.8128,2.8128,0,0,1,132.4388,21.0016Zm-6.0634,10.6508a8.4893,8.4893,0,1,0-8.4891,8.55A8.4882,8.4882,0,0,0,126.3754,31.6524ZM158.4191,18.18h0a2.8021,2.8021,0,0,0-2.5935,1.7411l-6.0122,14.6947a.6316.6316,0,0,1-1.2127,0l-5.8441-14.5126a3.07,3.07,0,0,0-2.8478-1.9232h0a3.07,3.07,0,0,0-2.835,4.2479L145.69,43.1656a1.6961,1.6961,0,0,1,0,1.334l-4.3122,10.0638a2.8268,2.8268,0,0,0,2.6,3.94h0a2.8268,2.8268,0,0,0,2.6085-1.7421l14.42-34.7035A2.8022,2.8022,0,0,0,158.4191,18.18Zm28.1974,3.0417.0022,20.9206a3.0326,3.0326,0,0,0,3.0326,3.0322h0a3.0325,3.0325,0,0,0,3.0325-3.0325V21.2213a3.0325,3.0325,0,0,0-3.0325-3.0325h-.0022A3.0326,3.0326,0,0,0,186.6165,21.2217Zm85.063-3.03a.849.849,0,0,1-.8494-.8488l-.0023-9.4607a3.0314,3.0314,0,0,0-3.0313-3.0307h0a3.0315,3.0315,0,0,0-3.0314,3.0317l.0011,9.46a.7483.7483,0,0,1-.7561.8464h-1.85a2.851,2.851,0,0,0-2.851,2.8522h0a2.8509,2.8509,0,0,0,2.85,2.85l1.8185.0005a.7881.7881,0,0,1,.788.7881l.0012,17.4655a3.03,3.03,0,0,0,3.03,3.03h0a3.03,3.03,0,0,0,3.03-3.03L270.83,24.68a.751.751,0,0,1,.8488-.7884l2.9715-.0006a2.85,2.85,0,0,0,2.85-2.85h0a2.85,2.85,0,0,0-2.8515-2.85Zm-23.7637-.91a9.5115,9.5115,0,0,0-7.0943,3.2138.63.63,0,0,1-1.0916-.4246L239.7333,3.6A3.0334,3.0334,0,0,0,236.7.5659h0a3.0334,3.0334,0,0,0-3.0335,3.0334l.0026,38.5434a3.0321,3.0321,0,0,0,3.0321,3.0318h0a3.032,3.032,0,0,0,3.0321-3.0328L239.73,28.3782a2.4293,2.4293,0,0,1,.4852-1.3946c1.6372-2.4861,3.5775-3.82,5.7-3.82,2.668,0,4.4263,1.516,4.4263,4.9116l-.0009,14.067a3.0322,3.0322,0,0,0,3.0322,3.0324h0a3.0321,3.0321,0,0,0,3.0322-3.0322V26.1347C256.4048,20.4956,253.0093,17.2818,247.9158,17.2818ZM227.1365,45.6593c0,8.4287-6.3063,13.7646-14.4318,13.7646a17.8789,17.8789,0,0,1-2.039-.1154,16.2665,16.2665,0,0,1-3.0295-.6393,14.3622,14.3622,0,0,1-2.68-1.1483,12.5992,12.5992,0,0,1-2.2573-1.5995,11.1305,11.1305,0,0,1-1.7627-1.9929c-.0712,0-.2521-.3684-.2894-.429q-.1537-.25-.2919-.5085a9.599,9.599,0,0,1-.4882-1.0655,3.7374,3.7374,0,0,1-.3125-1.7015,2.4593,2.4593,0,0,1,.3251-.9864,2.7561,2.7561,0,0,1,3.68-1.0318,3.3032,3.3032,0,0,1,1.19,1.2911,10.6261,10.6261,0,0,0,1.196,1.63,7.7528,7.7528,0,0,0,3.8806,2.4448l.0335.0077a12.0538,12.0538,0,0,0,4.18.3591,9.0075,9.0075,0,0,0,1.6784-.3759c3.1612-1.0407,5.3546-3.811,5.3546-7.9031V42.4458a.7.7,0,0,0-1.2128-.4852,10.9373,10.9373,0,0,1-8.1858,3.5171c-7.7007,0-13.643-6.3063-13.643-14.1285a13.7592,13.7592,0,0,1,13.643-14.0674,10.8407,10.8407,0,0,1,8.7922,4.2446c.364.4849.9544.3721,1.0308-.182.0065-.0474.0118-.197.016-.407a2.806,2.806,0,0,1,5.6114.056Zm-6.0639-14.1281a8.4893,8.4893,0,1,0-8.4891,8.55A8.45,8.45,0,0,0,221.0726,31.5312ZM202.6279,49.3519a2.7091,2.7091,0,0,0-.2005-.3356C202.4916,49.1268,202.5593,49.2389,202.6279,49.3519Zm-148.1337.4925L45.018,40.1333a3.007,3.007,0,0,0-2.1346-.9141h-.041a3.0349,3.0349,0,0,0-2.17,5.153l9.4764,9.7116a3.0093,3.0093,0,0,0,1.6907.8767,3.0513,3.0513,0,0,0,2.6016-.824,3.0348,3.0348,0,0,0,.053-4.2921Z" style="fill:#ee2657"/><g style="clip-path:url(#clip-path)"><g style="clip-path:url(#clip-path-2)"><image width="138" height="145" transform="translate(-2.88 -4.8) scale(0.48)" xlink:href="data:image/png;base64,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"/></g></g><path d="M20.0963,15.63,10.6205,5.9186a3.0351,3.0351,0,1,0-4.3453,4.2385l9.4762,9.7116a3.0351,3.0351,0,1,0,4.3449-4.239Z" style="fill:#524fa1"/></svg>
                        </header>

                        <article>
                            <h1>Enjoy what you want, right now, with easy, bitesized, installments</h1>
                            <ul class="icon-container">
                                <li>
                                    <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="70px" height="70px" viewBox="0 0 70 70"><path d="M50.9733,19.0117c-1.6664-3.3328-4.8235-5.1682-8.89-5.1682H19.9964a11.97,11.97,0,0,0-4.9446,1.223c-3.3328,1.6663-5.1682,4.8234-5.1682,8.89V46.0437a11.9728,11.9728,0,0,0,1.223,4.9446c1.6664,3.3328,4.8235,5.1682,8.89,5.1682H42.0835a11.97,11.97,0,0,0,4.9446-1.223c3.3327-1.6663,5.1682-4.8234,5.1682-8.89V41.4665a2.1175,2.1175,0,1,0-4.235,0v4.5772c0,2.4366-.89,4.0877-2.7213,5.0478a7.9042,7.9042,0,0,1-3.1663.83H19.9964c-2.4365,0-4.0876-.89-5.0477-2.7212a7.906,7.906,0,0,1-.83-3.1664V23.9563c0-2.4366.89-4.0877,2.7212-5.0478a7.9064,7.9064,0,0,1,3.166-.83H42.0835c2.4365,0,4.0876.89,5.0477,2.7212a7.8887,7.8887,0,0,1,.8079,2.8261l3.5114-3.5114A11.037,11.037,0,0,0,50.9733,19.0117Z" style="fill:#ee2657"/><path d="M59.4962,15.0634a2.1172,2.1172,0,0,0-2.9946,0l-5.0511,5.051-3.5114,3.5114L33.977,37.588l-7.1331-7.1334a2.1175,2.1175,0,1,0-2.9947,2.9944L32.48,42.08a2.1175,2.1175,0,0,0,1.4973.62h0a2.1175,2.1175,0,0,0,1.4973-.62l24.022-24.022A2.1174,2.1174,0,0,0,59.4962,15.0634Z" style="fill:#271952"/></svg></div>
                                    <div class="number-text">
                                        <div class="number">1.</div>
                                        <div class="text">Select your purchase online</div>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="70px" height="70px" viewBox="0 0 70 70"><path d="M10.4162,10.4162h26.812A22.3555,22.3555,0,0,1,59.5838,32.7717v4.4566A22.3555,22.3555,0,0,1,37.2283,59.5838H32.7717A22.3555,22.3555,0,0,1,10.4162,37.2283V10.4162A0,0,0,0,1,10.4162,10.4162Z" transform="translate(0 70) rotate(-90)" style="fill:none;stroke:#ed2557;stroke-miterlimit:10;stroke-width:4.4px"/><path d="M39.8683,39.8815a1.5027,1.5027,0,0,0-.025,2.0792l4.4616,4.7039a1.402,1.402,0,0,0,2.0208.0251,1.5,1.5,0,0,0,.025-2.0788L41.889,39.907a1.3955,1.3955,0,0,0-1.0048-.4425h-.0191a1.3981,1.3981,0,0,0-.9968.417m-9.737-9.7638a1.5007,1.5007,0,0,0,.0251-2.0792l-4.4613-4.704a1.4045,1.4045,0,0,0-2.0208-.0258,1.5009,1.5009,0,0,0-.025,2.0792l4.4617,4.7035a1.4008,1.4008,0,0,0,2.02.0263M46.03,33.485a7.55,7.55,0,0,0,2.0973-5.2528,7.7077,7.7077,0,0,0-.0833-1.1235,7.5659,7.5659,0,0,0-1.81-3.9092,7.1933,7.1933,0,0,0-4.6419-2.4169q-.1839-.0189-.37-.0291t-.3735-.01a6.9678,6.9678,0,0,0-.7432.0388A7.1933,7.1933,0,0,0,35.4641,23.2,7.51,7.51,0,0,0,34.23,25.1254a7.6481,7.6481,0,0,0-.6587,3.1068V41.663a4.7842,4.7842,0,0,1-.712,2.5237,4.6612,4.6612,0,0,1-.55.7235,4.398,4.398,0,0,1-1.5278,1.0766c-.04.0166-.0794.0332-.12.049-.0805.0307-.1623.0594-.2448.0845a4.0889,4.0889,0,0,1-.7568.165,4.1764,4.1764,0,0,1-.4345.03c-.025,0-.05.0008-.0758.0008a4.3238,4.3238,0,0,1-3.124-1.3342,4.5712,4.5712,0,0,1-1.2746-2.7631q-.0214-.2238-.0215-.4513a4.6083,4.6083,0,0,1,1.15-3.0559,4.3707,4.3707,0,0,1,2.82-1.4685c.0524-.0057.1068-.01.1647-.0134h.0072c.0563-.004.1151-.0064.1778-.0072h.15a1.3567,1.3567,0,0,0,.9762-.417,1.4391,1.4391,0,0,0,.4052-1.0038v-.0991A1.4029,1.4029,0,0,0,29.2,34.2821l-.0211-.0008a.5461.5461,0,0,0-.0809,0h0l-.0445.0008h-.0146A7.1421,7.1421,0,0,0,23.97,36.5142a7.5519,7.5519,0,0,0-2.0973,5.2536,7.6646,7.6646,0,0,0,.0484.846,7.5672,7.5672,0,0,0,1.8445,4.1867,7.1951,7.1951,0,0,0,4.6423,2.4169c.1222.013.2456.0223.3694.0287s.2488.01.3735.01a7.1327,7.1327,0,0,0,5.3855-2.4557,7.5251,7.5251,0,0,0,1.2346-1.9267,7.6412,7.6412,0,0,0,.6583-3.106V28.3366a4.79,4.79,0,0,1,.7124-2.5241,4.68,4.68,0,0,1,.55-.7235,4.4049,4.4049,0,0,1,1.5278-1.0766c.04-.0166.0794-.0332.1195-.0482.0805-.0315.1623-.059.2448-.0853.0746-.0238.15-.0449.2262-.0639q.1321-.0345.2659-.0594c.0877-.0158.1758-.0308.2647-.0417a4.289,4.289,0,0,1,.4345-.0291c.0251-.0008.05-.0008.0758-.0008a4.3218,4.3218,0,0,1,3.124,1.3338,4.5622,4.5622,0,0,1,1.2111,2.321,4.6964,4.6964,0,0,1,.085.8934,4.6076,4.6076,0,0,1-1.15,3.0559,4.37,4.37,0,0,1-2.8195,1.4685c-.052.0057-.1068.01-.1647.0134h-.0076c-.0559.0032-.1147.0056-.1774.0072h-.15a1.3582,1.3582,0,0,0-.9762.417,1.4347,1.4347,0,0,0-.4052,1.0042v.0987A1.4029,1.4029,0,0,0,40.8,35.7179l.0211.0008c.0266.0016.0539.0008.0813,0,.0147-.0008.0294-.0008.0445-.0008h.0079l.0068,0A7.1408,7.1408,0,0,0,46.03,33.485" style="fill:#271952"/></svg></div>
                                    <div class="number-text">
                                        <div class="number">2.</div>
                                        <div class="text">Select Payright as your preferred payment option</div>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="70px" height="70px" viewBox="0 0 70 70"><title>clipboard</title><path d="M55.4663,19.3472a10.15,10.15,0,0,0-.1313-1.4639,11.9219,11.9219,0,0,0-.5646-2.2221,9.8371,9.8371,0,0,0-.95-1.9449,8.3359,8.3359,0,0,0-1.15-1.4324,7.7622,7.7622,0,0,0-1.1606-.9492,7.9786,7.9786,0,0,0-2.2253-1.026,9.8066,9.8066,0,0,0-2.7307-.3665H23.4467a8.63,8.63,0,0,0-1.4344.1469,10.3037,10.3037,0,0,0-2.1447.6265,8.9732,8.9732,0,0,0-1.8624,1.0458,8.154,8.154,0,0,0-1.3465,1.2425,8.3128,8.3128,0,0,0-1.5494,2.7025,10.9487,10.9487,0,0,0-.5756,3.6408V50.872a9.6665,9.6665,0,0,0,.1328,1.4394,11.41,11.41,0,0,0,.57,2.1769,9.5171,9.5171,0,0,0,.9592,1.904A8.1851,8.1851,0,0,0,17.3533,57.79a7.7818,7.7818,0,0,0,2.5805,1.647,9.5848,9.5848,0,0,0,3.5129.62H46.5533l-.0671-.001c.0211.0007.0654.0017.1307.0017a9.5625,9.5625,0,0,0,1.7365-.175,9.8447,9.8447,0,0,0,2.1439-.661,8.4591,8.4591,0,0,0,1.782-1.06,7.8,7.8,0,0,0,1.2576-1.23,8.1611,8.1611,0,0,0,1.4138-2.61,10.947,10.947,0,0,0,.5156-3.4507V32.3643a2.1175,2.1175,0,0,0-4.235,0V50.872a7.211,7.211,0,0,1-.1972,1.761,4.5287,4.5287,0,0,1-.3989,1.0507,3.614,3.614,0,0,1-.4521.6578,3.7311,3.7311,0,0,1-.9731.7922,4.8139,4.8139,0,0,1-.8321.374,6.0139,6.0139,0,0,1-1.155.2692,4.91,4.91,0,0,1-.606.046h-.0128l.0163,0-.0163,0,.0163,0-.0671-.001H23.4467a5.7357,5.7357,0,0,1-1.64-.216,3.8029,3.8029,0,0,1-.9685-.4354,3.5014,3.5014,0,0,1-.6284-.5117,4.3719,4.3719,0,0,1-.7919-1.1521,6.1979,6.1979,0,0,1-.37-.9716,7.4909,7.4909,0,0,1-.2471-1.24c-.0174-.156-.0256-.2778-.0293-.3527-.0019-.0374-.0026-.063-.0029-.0753l0-.0082V19.3472a7.2073,7.2073,0,0,1,.2206-1.86A4.6015,4.6015,0,0,1,19.435,16.38a3.8314,3.8314,0,0,1,.5077-.6967,4.1472,4.1472,0,0,1,1.09-.8373,5.3728,5.3728,0,0,1,.9-.3822,6.2789,6.2789,0,0,1,1.1433-.2542c.1429-.0178.2536-.0261.32-.03.033-.0019.0548-.0026.064-.0028H46.5533a5.5094,5.5094,0,0,1,1.6227.22,3.7177,3.7177,0,0,1,.9609.4456,3.5454,3.5454,0,0,1,.6308.53,4.5871,4.5871,0,0,1,.8034,1.2058,6.612,6.612,0,0,1,.3761,1.0185,8.0632,8.0632,0,0,1,.251,1.2992c.0177.1637.0261.292.03.3715.002.04.0028.0673.0031.0811l0,.01v-.0118a2.1175,2.1175,0,0,0,4.235,0Z" style="fill:#ee2657"/><path d="M42.5827,26.4953H27.4174a2.1175,2.1175,0,0,1,0-4.235H42.5827a2.1175,2.1175,0,1,1,0,4.235Z" style="fill:#271952"/><path d="M42.5827,37.769H27.4174a2.1175,2.1175,0,0,1,0-4.235H42.5827a2.1175,2.1175,0,0,1,0,4.235Z" style="fill:#271952"/><path d="M33.4635,49.0427H27.4174a2.1175,2.1175,0,1,1,0-4.235h6.0461a2.1175,2.1175,0,0,1,0,4.235Z" style="fill:#271952"/></svg></div>
                                    <div class="number-text">
                                        <div class="number">3.</div>
                                        <div class="text">Enter your contact details to sign up or sign in to your Payright account</div>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="70px" height="70px" viewBox="0 0 70 70"><title>truck</title><path d="M7.6389,49.187a2.2065,2.2065,0,0,1-2.2-2.2V15.4188a2.2065,2.2065,0,0,1,2.2-2.2H41.0221a2.2065,2.2065,0,0,1,2.2,2.2V46.987a2.2065,2.2065,0,0,1-2.2,2.2H30.303" style="fill:none;stroke:#ee2657;stroke-linecap:round;stroke-linejoin:round;stroke-width:4.4px"/><path d="M48.7218,22.9533h7.8093a4.1929,4.1929,0,0,1,3.3,2.2l4.73,8.3595V47.152" style="fill:none;stroke:#ee2657;stroke-linecap:round;stroke-linejoin:round;stroke-width:4.4px"/><line x1="13.3813" y1="27.6495" x2="18.2488" y2="32.517" style="fill:none;stroke:#271952;stroke-linecap:round;stroke-linejoin:round;stroke-width:4.4px"/><line x1="22.603" y1="36.2479" x2="35.528" y2="23.3229" style="fill:none;stroke:#271952;stroke-linecap:round;stroke-linejoin:round;stroke-width:4.4px"/><circle cx="18.863" cy="50.8412" r="5.94" style="fill:none;stroke:#271952;stroke-linecap:round;stroke-linejoin:round;stroke-width:4.4px"/><circle cx="53.843" cy="50.8412" r="5.94" style="fill:none;stroke:#271952;stroke-linecap:round;stroke-linejoin:round;stroke-width:4.4px"/></svg></div>
                                    <div class="number-text">
                                        <div class="number">4.</div>
                                        <div class="text">Once approved your purchase will be dispatched</div>
                                    </div>
                                </li>
                            </ul>

                            <div class="text-big">
                                <p>Right when you need it</p>
                                <p>Payright lets you make payments with more choice and control.<br> Just Payright it, to make purchases more affordable,<br> so you can get what you want, right now and enjoy zero interest.</p>
                            </div>

                            <div class="important-info">
                                <h2>Important Information:</h2>
                                <p>We want to ensure you have a positive payment experience and have made the checkout process simple. We take responsible lending seriously so credit is only extended to approved customers. Please ensure you read the <a href="https://payright.com/terms-and-conditions/" target="_blank">terms and conditions</a> for further information and note that PayRight is not available on all purchases.</p>
                            </div>

                            <a href="https://www.payright.com.au/" class="tellmemore">Tell me more</a>
                        </article>
                     </div>-->
                    <!-- ***** END MODAL ***** -->
<div class="modal-content" data-role="content" id="modal-content-1">
        <!-- ***** START MODAL ***** -->
        <div class="payRight_container" id="PayrightHowitWorksmodalPopup">
            <header>
                <img alt="Payright" src="{$payright_base_url|escape:'htmlall':'UTF-8'}modules/payright/views/img/payrightlogo_rgb.png">
                </img>
            </header>
            <article>
                <h1>
                    Turn too much into too easy.
                </h1>
                <div class="icon-container light">
                    <ul>
                        <li>
                            <div class="icon">
                                <svg viewbox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M62.3954,38.6808a1.275,1.275,0,0,1-1.149-.721,3.6568,3.6568,0,0,1-.416-1.845,3.5734,3.5734,0,0,1,.428-1.845,1.2839,1.2839,0,0,1,1.137-.721,1.2511,1.2511,0,0,1,1.136.708,3.7389,3.7389,0,0,1,.403,1.858,3.6571,3.6571,0,0,1-.415,1.845,1.259,1.259,0,0,1-1.124.721m0,2.175a3.5051,3.5051,0,0,0,2.016-.623,4.26,4.26,0,0,0,1.442-1.711,5.4594,5.4594,0,0,0,.525-2.407,5.7608,5.7608,0,0,0-.5-2.432,4.1276,4.1276,0,0,0-1.406-1.711,3.7754,3.7754,0,0,0-4.155,0,4.2047,4.2047,0,0,0-1.417,1.711,5.6317,5.6317,0,0,0-.514,2.432,5.3579,5.3579,0,0,0,.538,2.407,4.3418,4.3418,0,0,0,1.454,1.711,3.5059,3.5059,0,0,0,2.017.623m-11.351-10.817a1.275,1.275,0,0,1-1.149-.721,3.6636,3.6636,0,0,1-.415-1.846,3.5686,3.5686,0,0,1,.428-1.845,1.2833,1.2833,0,0,1,1.136-.721,1.251,1.251,0,0,1,1.136.709,3.737,3.737,0,0,1,.404,1.857,3.6634,3.6634,0,0,1-.416,1.846,1.2569,1.2569,0,0,1-1.124.721m0,2.175a3.5178,3.5178,0,0,0,2.017-.623,4.2664,4.2664,0,0,0,1.442-1.711,5.4668,5.4668,0,0,0,.525-2.408,5.7413,5.7413,0,0,0-.501-2.431,4.1193,4.1193,0,0,0-1.405-1.711,3.7771,3.7771,0,0,0-4.156,0,4.206,4.206,0,0,0-1.417,1.711,5.962,5.962,0,0,0,0,4.839,4.171,4.171,0,0,0,1.43,1.711,3.59,3.59,0,0,0,2.065.623" style="fill:#26144f">
                                    </path>
                                    <path d="M50.6165,41.5262a1.248,1.248,0,0,0,1.002-.587l12.035-16.997a1.4906,1.4906,0,0,0,.245-.733,1.0658,1.0658,0,0,0-.415-.855,1.34,1.34,0,0,0-.856-.342,1.2493,1.2493,0,0,0-1.002.586l-12.035,16.997a1.4866,1.4866,0,0,0-.245.733,1.0629,1.0629,0,0,0,.416.856,1.3336,1.3336,0,0,0,.855.342" style="fill:#26144f">
                                    </path>
                                    <path d="M23.2358,59.0746a14.9394,14.9394,0,0,1-4.987-6.895,28.7815,28.7815,0,0,1-1.69-10.274,28.7853,28.7853,0,0,1,1.69-10.274,14.9364,14.9364,0,0,1,4.987-6.894,12.8179,12.8179,0,0,1,7.875-2.453,12.8209,12.8209,0,0,1,7.876,2.453,14.9574,14.9574,0,0,1,4.987,6.894,28.8075,28.8075,0,0,1,1.69,10.274,28.8036,28.8036,0,0,1-1.69,10.274,14.96,14.96,0,0,1-4.987,6.895,12.8329,12.8329,0,0,1-7.876,2.452,12.83,12.83,0,0,1-7.875-2.452m13.353-7.413q1.989-3.324,1.989-9.756t-1.989-9.756a6.1742,6.1742,0,0,0-10.955,0q-1.9905,3.3255-1.99,9.756,0,6.432,1.99,9.756a6.1742,6.1742,0,0,0,10.955,0" style="fill:#26144f">
                                    </path>
                                    <path d="M40,78.773a38.7735,38.7735,0,0,1-27.417-66.19,38.7749,38.7749,0,0,1,56.465,1.734,3.1553,3.1553,0,0,1-4.726,4.182A32.4631,32.4631,0,1,0,72.463,40a3.155,3.155,0,0,1,6.31,0A38.7735,38.7735,0,0,1,40,78.773" style="fill:#ef3855">
                                    </path>
                                </svg>
                            </div>
                            <div class="text">
                                Zero interest
                            </div>
                        </li>
                        <li>
                            <div class="icon">
                                <svg viewbox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M73.4474,78.7729H6.5514a6.0067,6.0067,0,0,1-6-6V17.8769a6.0067,6.0067,0,0,1,6-6h66.896a6.006,6.006,0,0,1,6,6v54.896a6.006,6.006,0,0,1-6,6m-66.895-6.002.008.005,66.887-.003c-.004,0,.001-.005.003-.009l-.003-54.887c0,.008-.005-.001-.008-.003l-66.888.003c.004,0-.001.005-.002.008l.002,54.888.001-.002" style="fill:#ef3855">
                                    </path>
                                    <path d="M25.8255,26.4508a3,3,0,0,1-3-3V4.2268a3,3,0,0,1,6,0v19.224a3,3,0,0,1-3,3" style="fill:#26144f">
                                    </path>
                                    <path d="M54.174,26.4508a3,3,0,0,1-3-3V4.2268a3,3,0,1,1,6,0v19.224a3,3,0,0,1-3,3" style="fill:#26144f">
                                    </path>
                                    <path d="M37.8869,48.1556a9.6417,9.6417,0,0,1,3.184,3.815,11.6008,11.6008,0,0,1,1.118,5.031,11.7312,11.7312,0,0,1-1.604,6.197,10.4761,10.4761,0,0,1-4.521,4.058,15.4626,15.4626,0,0,1-6.804,1.41,14.3673,14.3673,0,0,1-3.5-.438,12.5481,12.5481,0,0,1-3.062-1.166,2.9307,2.9307,0,0,1-1.798-2.625,3.2276,3.2276,0,0,1,.826-2.138,2.53,2.53,0,0,1,1.993-.972,3.8814,3.8814,0,0,1,2.09.729,7.4088,7.4088,0,0,0,4.617,1.604,5.8766,5.8766,0,0,0,5.079-2.941,6.3044,6.3044,0,0,0,.851-3.281,5.2793,5.2793,0,0,0-1.58-4.082,5.4246,5.4246,0,0,0-3.815-1.458,6.6626,6.6626,0,0,0-1.701.194q-.729.195-.875.243a7.3863,7.3863,0,0,1-2.09.389,2.1031,2.1031,0,0,1-1.701-.754,2.8117,2.8117,0,0,1-.632-1.871,2.91,2.91,0,0,1,.243-1.215,4.62,4.62,0,0,1,.826-1.166l7.534-8.214h-9.624a2.6573,2.6573,0,0,1-2.721-2.722,2.5005,2.5005,0,0,1,.777-1.871,2.6843,2.6843,0,0,1,1.944-.754h14.873a2.9723,2.9723,0,0,1,2.236.802,2.9116,2.9116,0,0,1,.778,2.114,3.3584,3.3584,0,0,1-1.167,2.333l-6.513,7.194a8.5782,8.5782,0,0,1,4.739,1.555" style="fill:#26144f">
                                    </path>
                                    <path d="M57.3522,35.0084a2.9139,2.9139,0,0,1,.826,2.114v28.093a2.7562,2.7562,0,0,1-.923,2.114,3.244,3.244,0,0,1-2.285.851,3.0691,3.0691,0,0,1-2.235-.851,2.8237,2.8237,0,0,1-.875-2.114V42.1774l-3.014,1.847a2.9674,2.9674,0,0,1-1.555.437,2.7038,2.7038,0,0,1-2.066-.923,2.9462,2.9462,0,0,1-.85-2.041,2.7637,2.7637,0,0,1,.413-1.459,3.01,3.01,0,0,1,1.093-1.069l7.34-4.374a4.4322,4.4322,0,0,1,2.09-.438,2.7345,2.7345,0,0,1,2.041.851" style="fill:#26144f">
                                    </path>
                                </svg>
                            </div>
                            <div class="text">
                                Convenient fortnightly or
                                <br>
                                    monthly installments
                                </br>
                            </div>
                        </li>
                        <li>
                            <div class="icon">
                                <svg viewbox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M59.0818,55.2651a2.8587,2.8587,0,0,0-.86-2.077l-5.532-4.47q-4.3875-3.5445-8.774-7.089-.5805-.468-1.16-.937v-22.87c0-1.113.021-2.229,0-3.343v-.047a2.937,2.937,0,1,0-5.874,0v24.022c0,1.113-.02,2.229,0,3.343.001.015,0,.031,0,.047,0,.069.004.139.01.208v.03a2.8564,2.8564,0,0,0,.86,2.076l5.532,4.47q4.3875,3.5445,8.774,7.09,1.005.8115,2.01,1.624a3.1775,3.1775,0,0,0,2.077.86,2.991,2.991,0,0,0,2.077-.86,2.9557,2.9557,0,0,0,.86-2.077" style="fill:#26144f">
                                    </path>
                                    <path d="M75.618,36.8448A3.1549,3.1549,0,0,0,72.463,40a32.4628,32.4628,0,1,1-8.141-21.501,3.155,3.155,0,0,0,4.726-4.181A38.774,38.774,0,1,0,78.773,40a3.1556,3.1556,0,0,0-3.155-3.155" style="fill:#ef3855">
                                    </path>
                                </svg>
                            </div>
                            <div class="text">
                                Terms of up to
                                <br>
                                    36 months
                                </br>
                            </div>
                        </li>
                        <li>
                            <div class="icon">
                                <svg viewbox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M24.2426,49.9533a7.3483,7.3483,0,0,1-3.7519,2.003v1.749A1.6868,1.6868,0,0,1,18.77,55.426a1.5848,1.5848,0,0,1-1.1846-.4942,1.6888,1.6888,0,0,1-.48-1.2265v-1.58a9.7944,9.7944,0,0,1-2.7793-.6484,8.9045,8.9045,0,0,1-2.44-1.5235,1.9812,1.9812,0,0,1-.7617-1.4951,1.5921,1.5921,0,0,1,.4795-1.1426,1.5569,1.5569,0,0,1,1.1572-.4941,1.4971,1.4971,0,0,1,.959.3389,10.087,10.087,0,0,0,1.664,1.1142,5.504,5.504,0,0,0,1.7217.55v-5.501q-5.67-1.0429-5.6709-5.67a5.0938,5.0938,0,0,1,1.58-3.8515,7.1142,7.1142,0,0,1,4.0908-1.8477v-.9307a1.6867,1.6867,0,0,1,1.7207-1.7216,1.5915,1.5915,0,0,1,1.1846.4941,1.6938,1.6938,0,0,1,.48,1.2275v1.044a8.1152,8.1152,0,0,1,3.9492,1.749,1.8811,1.8811,0,0,1,.8183,1.4951,1.5924,1.5924,0,0,1-.4794,1.1426,1.5241,1.5241,0,0,1-1.128.4932,1.2335,1.2335,0,0,1-.7617-.2539,7.3094,7.3094,0,0,0-2.3984-1.2129v5.2187a8.1988,8.1988,0,0,1,3.9775,1.9043,5.0242,5.0242,0,0,1,1.2412,3.6817A5.0371,5.0371,0,0,1,24.2426,49.9533ZM15.3559,39.0217a4.1451,4.1451,0,0,0,1.75.917V35.2551a3.3078,3.3078,0,0,0-1.75.8183,1.9586,1.9586,0,0,0-.5918,1.4385A2.0172,2.0172,0,0,0,15.3559,39.0217Zm6.4609,8.66a1.7357,1.7357,0,0,0,.5079-1.1982,2.1676,2.1676,0,0,0-.4512-1.3691,3.399,3.399,0,0,0-1.3828-.9727v4.4287A3.6925,3.6925,0,0,0,21.8168,47.6818Z" style="fill:#26144f">
                                    </path>
                                    <path d="M40.0122,49.2482a1.5307,1.5307,0,0,1,.4511,1.128,1.45,1.45,0,0,1-.4511,1.0859,1.5552,1.5552,0,0,1-1.129.4375H29.2915a1.5078,1.5078,0,0,1-1.1563-.4512,1.623,1.623,0,0,1-.4228-1.1572A1.75,1.75,0,0,1,28.22,49.05L34.37,42.4768a10.8819,10.8819,0,0,0,1.65-2.27,4.5373,4.5373,0,0,0,.6064-2.1016,3.0542,3.0542,0,0,0-.9023-2.2,2.8263,2.8263,0,0,0-2.0879-.9316,2.9777,2.9777,0,0,0-1.65.5507,6.0971,6.0971,0,0,0-1.5088,1.4522,1.5588,1.5588,0,0,1-1.2979.6777,1.6532,1.6532,0,0,1-1.1425-.48,1.4287,1.4287,0,0,1-.5225-1.0713,1.5861,1.5861,0,0,1,.2822-.86A6.4313,6.4313,0,0,1,28.643,34.24a8.5035,8.5035,0,0,1,2.4824-1.7207,6.4711,6.4711,0,0,1,2.708-.6485,6.5875,6.5875,0,0,1,3.2725.7891,5.4919,5.4919,0,0,1,2.1729,2.1728,6.3787,6.3787,0,0,1,.7617,3.1309,7.857,7.857,0,0,1-.917,3.541,15.072,15.072,0,0,1-2.4688,3.4834l-3.583,3.8086h5.8115A1.5322,1.5322,0,0,1,40.0122,49.2482Z" style="fill:#26144f">
                                    </path>
                                    <path d="M44.6821,50.9123A7.7388,7.7388,0,0,1,42.1,47.344a14.91,14.91,0,0,1-.874-5.3184,14.9079,14.9079,0,0,1,.874-5.3174A7.7284,7.7284,0,0,1,44.6821,33.14a7.1782,7.1782,0,0,1,8.1523,0,7.7331,7.7331,0,0,1,2.5811,3.5683,14.9072,14.9072,0,0,1,.875,5.3174,14.9094,14.9094,0,0,1-.875,5.3184,7.7434,7.7434,0,0,1-2.5811,3.5683,7.1787,7.1787,0,0,1-8.1523,0Zm6.9111-3.8369a9.9754,9.9754,0,0,0,1.03-5.05,9.98,9.98,0,0,0-1.03-5.05,3.1957,3.1957,0,0,0-5.67,0,9.97,9.97,0,0,0-1.03,5.05,9.9649,9.9649,0,0,0,1.03,5.05,3.1957,3.1957,0,0,0,5.67,0Z" style="fill:#26144f">
                                    </path>
                                    <path d="M67.8139,50.5861a1.174,1.174,0,0,1-.4267.92,1.3656,1.3656,0,0,1-.9092.372,1.2477,1.2477,0,0,1-.9629-.4384l-3.94-4.1592-1.0723.9853v2.2989a1.3141,1.3141,0,0,1-.3721.9521,1.349,1.349,0,0,1-1.8828,0,1.3145,1.3145,0,0,1-.3721-.9521V37.0363a1.3114,1.3114,0,0,1,.3721-.9521,1.3465,1.3465,0,0,1,1.8828,0,1.311,1.311,0,0,1,.3721.9521v8.1l4.6846-4.5322a1.3219,1.3219,0,0,1,.9629-.415,1.1672,1.1672,0,0,1,.8652.4043,1.2061,1.2061,0,0,1,.3828.8213,1.3177,1.3177,0,0,1-.5254.9853l-3.3057,3.042,3.8311,4.1592A1.4006,1.4006,0,0,1,67.8139,50.5861Z" style="fill:#26144f">
                                    </path>
                                    <path d="M42.3554,4.231a3.1675,3.1675,0,0,1-3.168,3.168A32.6058,32.6058,0,1,0,61.27,16.021l.291,10.695a3.1682,3.1682,0,0,1-6.334.172l-.595-21.864,21.864-.594a3.1682,3.1682,0,0,1,.172,6.334l-11.05.3-.177.186A38.9319,38.9319,0,1,1,39.1874,1.063a3.1675,3.1675,0,0,1,3.168,3.168" style="fill:#ef3855">
                                    </path>
                                </svg>
                            </div>
                            <div class="text">
                                For purchases
                                <br>
                                    up to $20k
                                </br>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="icon-container">
                    <h2>
                        How to shop...
                    </h2>
                    <ul>
                        <li>
                            <div class="icon">
                                <svg viewbox="0 0 55 55" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M27.5,51.02A23.52,23.52,0,1,0,3.98,27.5,23.52,23.52,0,0,0,27.5,51.02Z" style="fill:none;stroke:#fa3354;stroke-width:5px">
                                    </path>
                                    <path d="M31.75,16.1948a2.0413,2.0413,0,0,1,.5781,1.4795V37.3257a1.9293,1.9293,0,0,1-.6465,1.48A2.2711,2.2711,0,0,1,30.084,39.4a2.1525,2.1525,0,0,1-1.5645-.5947,1.9789,1.9789,0,0,1-.6123-1.48V21.21L25.8,22.5015a2.08,2.08,0,0,1-1.0879.3066,1.8917,1.8917,0,0,1-1.4453-.6465,2.06,2.06,0,0,1-.5947-1.4277,1.9252,1.9252,0,0,1,.289-1.0195,2.1023,2.1023,0,0,1,.7647-.7481l5.1338-3.0605A3.1071,3.1071,0,0,1,30.3213,15.6,1.9146,1.9146,0,0,1,31.75,16.1948Z" style="fill:#fff">
                                    </path>
                                </svg>
                            </div>
                            <div class="text">
                                Select your purchase online
                            </div>
                        </li>
                        <li>
                            <div class="icon">
                                <svg viewbox="0 0 55 55" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M27.5,51.02A23.52,23.52,0,1,0,3.98,27.5,23.52,23.52,0,0,0,27.5,51.02Z" style="fill:none;stroke:#fa3354;stroke-width:5px">
                                    </path>
                                    <path d="M34.7593,36.3735a1.8485,1.8485,0,0,1,.5439,1.36,1.7478,1.7478,0,0,1-.5439,1.3086,1.8783,1.8783,0,0,1-1.36.5273H21.8394a1.82,1.82,0,0,1-1.3946-.5439,1.957,1.957,0,0,1-.51-1.3946,2.1091,2.1091,0,0,1,.6123-1.4951l7.4111-7.9228a13.1335,13.1335,0,0,0,1.9893-2.7363,5.4712,5.4712,0,0,0,.7314-2.5333,3.6853,3.6853,0,0,0-1.0879-2.6523,3.4085,3.4085,0,0,0-2.5166-1.1221,3.6,3.6,0,0,0-1.9893.6631,7.39,7.39,0,0,0-1.8183,1.751,1.8777,1.8777,0,0,1-1.5645.8164,1.9926,1.9926,0,0,1-1.3769-.5781,1.7233,1.7233,0,0,1-.6289-1.292,1.91,1.91,0,0,1,.34-1.0371,7.7209,7.7209,0,0,1,1.02-1.2071,10.2705,10.2705,0,0,1,2.9922-2.0742A7.81,7.81,0,0,1,27.313,15.43a7.9489,7.9489,0,0,1,3.9443.9511A6.6181,6.6181,0,0,1,33.8745,19a7.6892,7.6892,0,0,1,.918,3.7744,9.4551,9.4551,0,0,1-1.1045,4.2666A18.1616,18.1616,0,0,1,30.7124,31.24L26.395,35.83h7.0039A1.8485,1.8485,0,0,1,34.7593,36.3735Z" style="fill:#fff">
                                    </path>
                                </svg>
                            </div>
                            <div class="text">
                                Select Payright as your
                                <br>
                                    preferred payment option
                                </br>
                            </div>
                        </li>
                        <li>
                            <div class="icon">
                                <svg viewbox="0 0 55 55" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M27.5,51.02A23.52,23.52,0,1,0,3.98,27.5,23.52,23.52,0,0,0,27.5,51.02Z" style="fill:none;stroke:#fa3354;stroke-width:5px">
                                    </path>
                                    <path d="M32.1748,25.2222a6.7306,6.7306,0,0,1,2.2275,2.6689,8.6517,8.6517,0,0,1-.34,7.8535A7.3216,7.3216,0,0,1,30.9,38.5835a10.8261,10.8261,0,0,1-4.76.9863,10.0639,10.0639,0,0,1-2.4492-.3056,8.8112,8.8112,0,0,1-2.1416-.8164,2.0488,2.0488,0,0,1-1.2578-1.836,2.2589,2.2589,0,0,1,.5781-1.4961,1.7683,1.7683,0,0,1,1.3936-.68,2.7211,2.7211,0,0,1,1.4629.51,5.179,5.179,0,0,0,3.2295,1.1221,4.1125,4.1125,0,0,0,3.5527-2.0567,4.4136,4.4136,0,0,0,.5947-2.2949A3.6934,3.6934,0,0,0,29.999,28.86,3.7977,3.7977,0,0,0,27.33,27.84a4.6313,4.6313,0,0,0-1.19.1358q-.5112.1362-.6133.17a5.1774,5.1774,0,0,1-1.4609.2725,1.47,1.47,0,0,1-1.19-.5274,1.9665,1.9665,0,0,1-.4424-1.31,2.0366,2.0366,0,0,1,.17-.85,3.2435,3.2435,0,0,1,.5781-.8164l5.27-5.7451H21.72a1.8575,1.8575,0,0,1-1.9033-1.9043,1.7523,1.7523,0,0,1,.543-1.31,1.8815,1.8815,0,0,1,1.36-.5263H32.124a2.0776,2.0776,0,0,1,1.5635.5605,2.0331,2.0331,0,0,1,.5449,1.4795,2.3514,2.3514,0,0,1-.8164,1.6319l-4.5566,5.0322A5.99,5.99,0,0,1,32.1748,25.2222Z" style="fill:#fff">
                                    </path>
                                </svg>
                            </div>
                            <div class="text">
                                Enter your contact details to sign up
                                <br>
                                    or sign in to your Payright account
                                </br>
                            </div>
                        </li>
                        <li>
                            <div class="icon">
                                <svg viewbox="0 0 55 55" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M27.5,51.02A23.52,23.52,0,1,0,3.98,27.5,23.52,23.52,0,0,0,27.5,51.02Z" style="fill:none;stroke:#fa3354;stroke-width:5px">
                                    </path>
                                    <path d="M36.1021,29.7437a1.8482,1.8482,0,0,1,.5439,1.36,1.7478,1.7478,0,0,1-.5439,1.3086,1.8749,1.8749,0,0,1-1.36.5273H33.314v4.3858A2.0336,2.0336,0,0,1,31.24,39.4a1.911,1.911,0,0,1-1.4277-.5947,2.0376,2.0376,0,0,1-.5781-1.48V32.94h-8.84A2.0264,2.0264,0,0,1,19,32.3452a1.8594,1.8594,0,0,1-.6455-1.4111,2.076,2.076,0,0,1,.51-1.36L29.6079,16.4155a2.0627,2.0627,0,0,1,1.7-.8154,1.9124,1.9124,0,0,1,1.4277.5947,2.0414,2.0414,0,0,1,.5782,1.4795V29.2h1.4277A1.8453,1.8453,0,0,1,36.1021,29.7437ZM23.7261,29.2h5.5078V22.4Z" style="fill:#fff">
                                    </path>
                                </svg>
                            </div>
                            <div class="text">
                                Once approved your
                                <br>
                                    purchase will be dispatched
                                </br>
                            </div>
                        </li>
                    </ul>
                </div>
                <h1>
                    Right when you need it – Payright
                </h1>
                <div class="important-info">
                    <h3>
                        Important Information:
                    </h3>
                    <p>
                        We want to ensure you have a positive payment experience and have made the checkout process simple. We take responsible lending seriously so credit is only extended to approved customers. Please ensure you read the <a href="https://payright.com/terms-and-conditions/" target="_blank">terms and conditions</a>for further information and note that PayRight is not available on all purchases.
                    </p>
                </div>
                <a class="tellmemore" href="https://www.payright.com.au/" target=" ">Tell me more</a>
            </article>
        </div>
        <!-- ***** END MODAL ***** -->
    </div>
              
<!--end of modal -->



  <!--   </div>
  </div> -->

</div>

{block name=head}
<script type="text/javascript" src="/modules/payright/views/js/modals.js" ></script>
{/block}

