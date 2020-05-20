=== SimplePay WooCommerce Payment Gateway ===
Contributors: tubiz
Donate link: https://bosun.me/donate
Tags: woocommerce, payment gateway, payment gateways, mastercard, visa cards, mastercards, interswitch, verve cards, tubiz plugins, verve, nigeria, simplepay
Requires at least: 4.4
Tested up to: 4.8
Stable tag: 2.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

SimplePay Woocommerce Payment Gateway allows you to accept local and International payment via MasterCard, Visa and Verve Cards.





== Description ==

This is a SimplePay payment gateway for WooCommerce.

SimplePay is an payment gateway that allows you to accept payments online, the simple way.

To get a Simplepay merchant account visit their website by clicking [here](https://www.simplepay.ng)

Simplepay Woocommerce Payment Gateway allows you to accept local and International payment on your Woocommerce store via MasterCard, Visa and Verve Cards.

With this Simplepay Woocommerce Payment Gateway plugin, you will be able to accept the following payment methods in your shop:

* __MasterCard__
* __Visa__
* __Verve Card__

= Note =

This plugin is meant to be used by merchants in Nigeria.

= Plugin Features =

*   __Accept payment__ via MasterCard, Visa and Verve Cards.
* 	__Seamless integration__ into the WooCommerce checkout page.


= Suggestions / Feature Request =

If you have suggestions or a new feature request, feel free to get in touch with me via the contact form on my website [here](http://bosun.me/get-in-touch/)

You can also follow me on Twitter! **[@tubiz](http://twitter.com/tubiz)**


= Contribute =
To contribute to this plugin feel free to fork it on GitHub [SimplePay Woocommerce Payment Gateway on GitHub](https://github.com/tubiz/simplepay-woocommerce-payment-gateway)


== Installation ==

= Automatic Installation =
* 	Login to your WordPress Admin area
* 	Go to "Plugins > Add New" from the left hand menu
* 	In the search box type "Simplepay Woocommerce Payment Gateway"
*	From the search result you will see "Simplepay Woocommerce Payment Gateway" click on "Install Now" to install the plugin
*	A popup window will ask you to confirm your wish to install the Plugin.

= Note: =
If this is the first time you've installed a WordPress Plugin, you may need to enter the FTP login credential information. If you've installed a Plugin before, it will still have the login information. This information is available through your web server host.

* Click "Proceed" to continue the installation. The resulting installation screen will list the installation as successful or note any problems during the install.
* If successful, click "Activate Plugin" to activate it.
* 	Open the settings page for WooCommerce and click the "Payment Gateways," tab.
* 	Click on the sub tab for "Simplepay".
*	Configure your "Simplepay" settings. See below for details.

= Manual Installation =
1. 	Download the plugin zip file
2. 	Login to your WordPress Admin. Click on "Plugins > Add New" from the left hand menu.
3.  Click on the "Upload" option, then click "Choose File" to select the zip file from your computer. Once selected, press "OK" and press the "Install Now" button.
4.  Activate the plugin.
5. 	Open the settings page for WooCommerce and click the "Payment Gateways," tab.
6. 	Click on the sub tab for "SimplePay".
7.	Configure your "SimplePay" settings. See below for details.



= Configure the plugin =
To configure the plugin, go to __WooCommerce > Settings__ from the left hand menu, then click "Payment Gateways" from the top tab. You should see __"SimplePay"__ as an option at the top of the screen. Click on it to configure the payment gateway.

__*You can select the radio button next to SimplePay from the list of payment gateways available to make it the default gateway.*__

* __Enable/Disable__ - check the box to enable SimplePay Payment Gateway.
* __Title__ - allows you to determine what your customers will see this payment option as on the checkout page.
* __Description__ - controls the message that appears under the payment fields on the checkout page. Here you can list the types of cards you accept.
* __Logo URL__  - enter the full url to your store/site logo. This will be shown on the SimplePay payment page
* __Public Test Key__  - enter your Public Test Key here. You will get this in your SimplePay merchant account [SimplePay](https://www.simplepay.ng).
* __Private Test Key__  - enter your Private Test Key here. You will get this in your SimplePay merchant account [SimplePay](https://www.simplepay.ng).
* __Public Live Key__  - enter your Public Live Key here. You will get this in your SimplePay merchant account [SimplePay](https://www.simplepay.ng).
* __Private Live Key__  - enter your Private Live Key here. You will get this in your SimplePay merchant account [SimplePay](https://www.simplepay.ng).
* __Test Mode__  - tick to enable test mode.
* Click on __Save Changes__ for the changes you made to be effected.





== Frequently Asked Questions ==

= What Do I Need To Use The Plugin =

1.	You need to have the WooCommerce plugin installed and activated on your WordPress site.
2.	You need to open a merchant account on [Simplepay](https://www.simplepay.ng)




== Changelog ==

= 2.2.0 =
*	Fix: Deprecated WooCommerce order function

= 2.1.0 =
* 	Fix: Change payment charge url

= 2.0.2 =
* 	Fix: Payment method icon not showing
*	Fix: Payment not being verified issue with token verification url

= 2.0.1 =
* 	Fix: Change token verification url

= 2.0.0 =
* 	New: Update plugin to use the new SimplePay platform.

= 1.2.0 =
*	Fix: Don't set order status to on-hold if the customer is paying Simplepay fee or gateway fee after a successful payment

= 1.1.0 =
*	Fix: Use wc_get_order instead or declaring a new WC_Order class
*	Fix: Removed all global $woocommerce variable

= 1.0.1 =
* 	Fix: Added payment icon missing in version 1.0.0

= 1.0.0 =
*   First release





== Upgrade Notice ==

= 2.2.0 =
*	Fix: Deprecated WooCommerce order function


== Screenshots ==

1. SimplePay Woocommerce Payment Gateway setting page

2. Test Mode notification, always displayed in the admin backend until when test mode is disabled

3. SimplePay Wooocommerce Payment Gateway method on the checkout page

4. Successful Transaction page