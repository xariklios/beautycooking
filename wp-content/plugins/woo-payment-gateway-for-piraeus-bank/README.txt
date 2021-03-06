=== Piraeus Bank WooCommerce Payment Gateway ===
Contributors: enartia,g.georgopoulos,georgekapsalakis
Author URI: https://www.enartia.com
Tags: ecommerce, woocommerce, payment gateway
Tested up to: 5.4.1
Requires at least: 4.0
Stable tag: 1.5.6
WC tested up to: 4.1.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Adds Piraeus Bank paycenter as a payment Gateway for WooCommerce

== Important Notice == 
1. Piraeus Bank has announced that it will gradually abolish the Preauthorized Payment Service for all merchants, beginning from the ones obtained MIDs from 29/1/2019 onwards.
You are highly recommended to disable the preAuthorized Payment Service as soon as possible.

2. We would like to inform you that our Plugin is compatible with the upcoming change in the way that the ” Hash Key” is generated by Piraeus Bank.
The only actions you should take are:
    * Please make sure you have the latest version of our plugin (version: 1.4.2).
    * Then email the ePOSPaycenter technical support team (epayments@piraeusbank.gr), indicating your MerchantID, so that the Bank can make the corresponding parameterisation

== Description ==
This plugin adds Piraeus Bank paycenter as a payment gateway for WooCommerce. A contract between you and the Bank must be previously signed. Based on original plugin "Piraeus Bank Greece Payment Gateway for WooCommerce" by emspace.gr [https://wordpress.org/plugins/woo-payment-gateway-piraeus-bank-greece/]

It uses the redirect method, and SSL is not required.


Requires SOAP installed in the server / hosting.
== Features ==
Provides pre-auth transactions and free instalments.

== Installation ==

Just follow the standard [WordPress plugin installation procedure](http://codex.wordpress.org/Managing_Plugins).

Provide to Piraeus bank at epayments@piraeusbank.gr the following information, in order to provide you with test account information. 
WITH PERMALINKS SET
* Website url :  http(s)://www.yourdomain.gr/
* Referrer url : http(s)://www.yourdomain.gr/checkout/
* Success page :  http(s)://www.yourdomain.gr/wc-api/WC_Piraeusbank_Gateway?peiraeus=success
* Failure page : http(s)://www.yourdomain.gr/wc-api/WC_Piraeusbank_Gateway?peiraeus=fail
* Cancel page : http(s)://www.yourdomain.gr/wc-api/WC_Piraeusbank_Gateway?peiraeus=cancel

WITHOUT PERMALINKS (MODE=SIMPLE)
* Website url :  http(s)://www.yourdomain.gr/
* Referrer url : http(s)://www.yourdomain.gr/checkout/
* Success page :  http(s)://www.yourdomain.gr/?wc-api=WC_Piraeusbank_Gateway&peiraeus=success
* Failure page : http(s)://www.yourdomain.gr/?wc-api=WC_Piraeusbank_Gateway&peiraeus=fail
* Cancel page : http(s)://www.yourdomain.gr/?wc-api=WC_Piraeusbank_Gateway&peiraeus=cancel

Response method : GET / POST
Your's server IP Address 

=== HTTP Proxy ===
In case your server doesn't provide a static IP address for your website, you can use an HTTP Proxy for outgoing requests from the server to the bank. The following fields need to be filled for http proxying:
HTTP Proxy Hostname: Required. If empty then HTTP Proxy is not used.
HTTP Proxy Port: Required if HTTP Proxy Hostname is filled.
HTTP Proxy Login Username/Password: Optional.



== Frequently asked questions ==


== Changelog ==

= 1.5.6 = 
update compatibility status with WooCommerce 4.1.0

= 1.5.5 = 
update compatibility status with WooCommerce 4

= 1.5.4 = 
fix release version

= 1.5.3 = 
Update translations

= 1.5.2 = 
Added max size for Logo of Piraeus Bank

= 1.5.1 = 
For downloadable products, auto mark the order as completed only if all the products are downloadable
Update translations
Added option to display or not Piraeus Bank's logo in checkout page.

= 1.5.0 = 
POST response method is now available
Added Max number of instalments based on order total
Support for English, German and Russian language in redirect page.

= 1.4.2 =
Fix issue for failed status of order but with paid transaction 

= 1.4.1 =
Bug Fixes (Pay again, after failed payment attempt)

= 1.4.0 =
New Piraeus API encryption algorithm

= 1.3 =
Added Proxy configuration option.

= 1.0.6 =
WooCommerce backwards compatible

= 1.0.4 =
WooCommerce 3.0 compatible

= 1.0.3 =
Text changed. New Title[GR]: Με κάρτα μέσω Πειραιώς

= 1.0.2 =
Bug Fixes

= 1.0.1 =
Bug Fixes

= 1.0.0 =
Initial Release

