## payright-prestashop
The Payright plugin for PrestaShop.

Give your customers the option to pay in convenient zero-interest instalments.

Payright helps turn ‘too much’ into ‘too easy’ by spreading the cost of purchases over time. The "Payright" module provides the option to choose Payright as the payment method at the checkout.

It also provides the functionality to display the Payright logo and instalment calculations on the Front page, Product page, Category Page and related Products page. For each payment that is approved by Payright, an order will be created inside the Prestashop system like any other order. Payright plans will activate once a product is shipped.

Please follow the steps below for setup, installation and configuration.

### Installation

This section outlines the steps to install the Payright plugin for the first time.

#### Requirements
+ Access Token - A 'sandbox access token', or 'production access token'.
+ Prestashop - Minimum version 1.7.x

#### How to Install
1. Download the 'payright-prestashop' plugin.
2. Navigate to the 'Module Manager' page
3. Click on the 'Upload a Module' button
4. Select the 'payright-prestashop' .zip file from your computer

### Configuration - PrestaShop
Payright operates under assumpption based on PrestaShop configurations. To align with these assumptions, the Prestashop configurations must reflect the below:

1. Payment Preference - Currency Restrictions: Payright has been selected for the merchant's currency (AUD and/or NZD)
2. Payment Preference - Country Restrictions: Payright has been selected for the selected country (Australia and/or New Zealand)

### How it Works

#### Activate Payright plan with order shipment

1. When a customer returns after a successful payment through Payright, the payment status will be set as 'Payment accepted'
2. Once the order is shipped, the payment status will be "Shipped"
3. On the order details page, change the status to 'Shipped'
4. Once the shipment is completed, the plan will be Activated

The plan is activated when the order has been marked as shipped. 

To further manage your customer's Payright plans, log in with your Payright merchant account at the Merchant Portal (https://merchant.payright.com.au/).

#### Pre-requisite Plugin Configuration

Complete the below steps to configure your Payright plugin:

1. Go to Module Manager page and click 'Configure'
2. Define your 'Access Token'
3. Select your 'Region / Country', 'Australia' or 'New Zealand'
4. Set the Payright plugin mode, toggling the 'Sandbox' Mode:
    + Toggle 'Enabled' on the Sandbox for testing on a staging instance
    + Toggle 'Disabled' for a live store and legitimate transactions
7. Click 'Save Changes'

#### Optional Configuration
Optional Configurations on the Module's configuration page can be changed: 

1. Configure the display of the Payright installments details on:
    + Product Page (Recommended)
    + Category Page (Recommended)
    + Front Page (Optional)
    + Related Products Page (Optional)
2. Enter a 'Minimum Amount' to display the installments

### Resource Links

1. Developer Portal - https://developers.payright.com.au
2. Merchant Portal - https://merchant.payright.com.au
3. Marketing Kit - https://payright.com.au/im-a-business/resources/integration-resources