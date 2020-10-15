<?php

/*
  Plugin Name: Piraeus Bank WooCommerce Payment Gateway
  Plugin URI: http://www.enartia.com
  Description: Piraeus Bank Payment Gateway allows you to accept payment through various channels such as Maestro, Mastercard, AMex cards, Diners  and Visa cards On your Woocommerce Powered Site.
  Version: 1.5.6
  Author: Enartia
  Author URI: https://www.enartia.com
  License: GPL-3.0+
  License URI: http://www.gnu.org/licenses/gpl-3.0.txt
  WC tested up to: 4.1.0
  Text Domain: woo-payment-gateway-for-piraeus-bank
  Domain Path: /languages
*/
 /*
 Based on original plugin "Piraeus Bank Greece Payment Gateway for WooCommerce" by emspace.gr [https://wordpress.org/plugins/woo-payment-gateway-piraeus-bank-greece/]
 */

if (!defined('ABSPATH'))
    exit;

add_action('plugins_loaded', 'woocommerce_piraeusbank_init', 0);

function woocommerce_piraeusbank_init() {

    if (!class_exists('WC_Payment_Gateway'))
        return;

    load_plugin_textdomain('woo-payment-gateway-for-piraeus-bank', false, dirname(plugin_basename(__FILE__)) . '/languages/');

    /**
     * Gateway class
     */
    class WC_Piraeusbank_Gateway extends WC_Payment_Gateway {

        public function __construct() {
            global $woocommerce;

            $this->id = 'piraeusbank_gateway';
            //$this->icon = apply_filters('piraeusbank_icon', plugins_url('img/PB_blue_GR.png', __FILE__));
            $this->has_fields = true;
            $this->notify_url = WC()->api_request_url('WC_Piraeusbank_Gateway');
            $this->method_description = __('Piraeus bank Payment Gateway allows you to accept payment through various channels such as Maestro, Mastercard, AMex cards, Diners  and Visa cards On your Woocommerce Powered Site.', 'woo-payment-gateway-for-piraeus-bank');
            $this->redirect_page_id = $this->get_option('redirect_page_id');
            $this->method_title = 'Piraeus bank  Gateway';

            // Load the form fields.
            $this->init_form_fields();



            global $wpdb;

            if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "piraeusbank_transactions'") === $wpdb->prefix . 'piraeusbank_transactions') {
                // The database table exist
            } else {
                // Table does not exist
                $query = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'piraeusbank_transactions (id int(11) unsigned NOT NULL AUTO_INCREMENT, merch_ref varchar(50) not null, trans_ticket varchar(32) not null , timestamp datetime default null, PRIMARY KEY (id))';
                $wpdb->query($query);
            }


            // Load the settings.
            $this->init_settings();


            // Define user set variables
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->pb_PayMerchantId = $this->get_option('pb_PayMerchantId');
            $this->pb_AcquirerId = $this->get_option('pb_AcquirerId');
            $this->pb_PosId = $this->get_option('pb_PosId');
            $this->pb_Username = $this->get_option('pb_Username');
            $this->pb_Password = $this->get_option('pb_Password');
            $this->pb_ProxyHost = $this->get_option('pb_ProxyHost');
            $this->pb_ProxyPort = $this->get_option('pb_ProxyPort');
            $this->pb_ProxyUsername = $this->get_option('pb_ProxyUsername');
            $this->pb_ProxyPassword = $this->get_option('pb_ProxyPassword');
            $this->pb_authorize = $this->get_option('pb_authorize');
            $this->pb_installments = $this->get_option('pb_installments');
            $this->pb_installments_variation = $this->get_option('pb_installments_variation');
            $this->pb_render_logo = $this->get_option('pb_render_logo');
            //Actions
            add_action('woocommerce_receipt_piraeusbank_gateway', array($this, 'receipt_page'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

            // Payment listener/API hook
            add_action('woocommerce_api_wc_piraeusbank_gateway', array($this, 'check_piraeusbank_response'));

            if(class_exists("SOAPClient") != true) {
                add_action( 'admin_notices', array($this, 'soap_error_notice'));
            }

            if ($this->pb_authorize == "yes") {
                add_action('admin_notices', array($this, 'authorize_warning_notice'));
            }
            if($this->pb_render_logo == "yes") {
                $this->icon = apply_filters('piraeusbank_icon', plugins_url('img/piraeusbank.svg', __FILE__));
            }
        }

        /**
         * Admin Panel Options
         * */
        public function admin_options() {
            echo '<h3>' . __('Piraeus Bank Gateway', 'woo-payment-gateway-for-piraeus-bank') . '</h3>';
            echo '<p>' . __('Piraeus Bank Gateway allows you to accept payment through various channels such as Maestro, Mastercard, AMex cards, Diners  and Visa cards.', 'woo-payment-gateway-for-piraeus-bank') . '</p>';


            echo '<table class="form-table">';
            $this->generate_settings_html();
            echo '</table>';
            // $host = (is_ssl() == true ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/'; 
            // echo '<div>';
            // echo '<h4>Στοιχεία για διασύνδεση με την τράπεζα</h4>';
            // echo '<ul>';

            // echo '<li>Website URL: ' . $host . '</li>';
            // echo '<li>Referrer url: '. $host . (get_option('permalink_structure') ? 'checkout/'  : 'checkout/' ) .' </li>';
            // echo '<li>Success page: '. $host . (get_option('permalink_structure') ? 'wc-api/WC_Piraeusbank_Gateway?peiraeus=success'  : '?wc-api=WC_Piraeusbank_Gateway&peiraeus=success' ) .' </li>';
            // echo '<li>Failure page: '. $host . (get_option('permalink_structure') ? 'wc-api/WC_Piraeusbank_Gateway?peiraeus=fail'  : '?wc-api=WC_Piraeusbank_Gateway&peiraeus=fail' ) .' </li>';
            // echo '<li>Cancel page: '. $host . (get_option('permalink_structure') ? 'wc-api/WC_Piraeusbank_Gateway?peiraeus=cancel'  : '?wc-api=WC_Piraeusbank_Gateway&peiraeus=cancel' ) .' </li>';
            // echo '<li>Response method : GET / POST </li>';
            // echo '<li>Server Ip: ' . $_SERVER['SERVER_ADDR'] . '</li>';
            // echo '</ul>';
            // echo '<pre>'; print_r($_SERVER); echo '</pre>';
            // // echo '<li>Website url: '.(get_option('permalink_structure') ? ''  : ) .'</li>';
            // echo '</div>';
        }
        function soap_error_notice() {
                echo '<div class="error notice">';
                echo '<p>'. __( '<strong>SOAP have to be enabled in your Server/Hosting</strong>, it is required for this plugin to work properly!', 'woo-payment-gateway-for-piraeus-bank') . '</p>';
                echo '</div>';
        }
        function authorize_warning_notice() {
                echo '<div class="notice-warning notice">';
                echo '<p>'. __( '<strong>Important Notice:</strong> Piraeus Bank has announced that it will gradually abolish the Preauthorized Payment Service for all merchants, beginning from the ones obtained MIDs from 29/1/2019 onwards.<br /> You are highly recommended to disable the preAuthorized Payment Service as soon as possible.', 'woo-payment-gateway-for-piraeus-bank') . '</p>';
                echo '</div>';
        }
        /**
         * Initialise Gateway Settings Form Fields
         * */
        function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'checkbox',
                    'label' => __('Enable Piraeus Bank Gateway', 'woo-payment-gateway-for-piraeus-bank'),
                    'description' => __('Enable or disable the gateway.', 'woo-payment-gateway-for-piraeus-bank'),
                    'desc_tip' => true,
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => __('Title', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'woo-payment-gateway-for-piraeus-bank'),
                    'desc_tip' => false,
                    'default' => __('Piraeus Bank Gateway', 'woo-payment-gateway-for-piraeus-bank')
                ),
                'description' => array(
                    'title' => __('Description', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'textarea',
                    'description' => __('This controls the description which the user sees during checkout.', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => __('Pay Via Piraeus Bank: Accepts  Mastercard, Visa cards and etc.', 'woo-payment-gateway-for-piraeus-bank')
                ),
                'pb_render_logo' => array(
                    'title' => __('Display the logo of Piraeus Bank', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'checkbox',
                    'description' => __('Enable to display the logo of Piraeus Bank next to the title which the user sees during checkout.', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => 'yes'
                ),
                'pb_PayMerchantId' => array(
                    'title' => __('Piraeus Bank Merchant ID', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('Enter Your Piraeus Bank Merchant ID', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'pb_AcquirerId' => array(
                    'title' => __('Piraeus Bank Acquirer ID', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('Enter Your Piraeus Bank Acquirer ID', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'pb_PosId' => array(
                    'title' => __('Piraeus Bank POS ID', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('Enter your Piraeus Bank POS ID', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => '',
                    'desc_tip' => true
                ), 'pb_Username' => array(
                    'title' => __('Piraeus Bank Username', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('Enter your Piraeus Bank Username', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => '',
                    'desc_tip' => true
                ), 'pb_Password' => array(
                    'title' => __('Piraeus Bank Password', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'password',
                    'description' => __('Enter your Piraeus Bank Password', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => '',
                    'desc_tip' => true
                ),
                'pb_ProxyHost' => array(
                    'title' => __('HTTP Proxy Hostname', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('Used when your server is not behind a static IP. Leave blank for normal HTTP connection.', 'woo-payment-gateway-for-piraeus-bank'),
                    'desc_tip' => false,
                    'default' => ''
                ),
                'pb_ProxyPort' => array(
                    'title' => __('HTTP Proxy Port', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('Used with Proxy Host.', 'woo-payment-gateway-for-piraeus-bank'),
                    'desc_tip' => false,
                    'default' => ''
                ),
                'pb_ProxyUsername' => array(
                    'title' => __('HTTP Proxy Login Username', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'text',
                    'description' => __('Used with Proxy Host. Leave blank for anonymous connection.', 'woo-payment-gateway-for-piraeus-bank'),
                    'desc_tip' => false,
                    'default' => ''
                ),
                'pb_ProxyPassword' => array(
                    'title' => __('HTTP Proxy Login Password', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'password',
                    'description' => __(' Used with Proxy Host. Leave blank for anonymous connection.', 'woo-payment-gateway-for-piraeus-bank'),
                    'desc_tip' => false,
                    'default' => ''
                ),
                'pb_authorize' => array(
                    'title' => __('Pre-Authorize', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'checkbox',
                    'label' => __('Enable to capture preauthorized payments', 'woo-payment-gateway-for-piraeus-bank'),
                    'default' => 'no',
                    'description' => __('<strong>Important Notice:</strong> Piraeus Bank has announced that it will gradually abolish the Preauthorized Payment Service for all merchants, beginning from the ones obtained MIDs from 29/1/2019 onwards.<br /> Default payment method is Purchase, enable for Pre-Authorized payments. You will then need to accept them from Peiraeus Bank AdminTool', 'woo-payment-gateway-for-piraeus-bank')
                ),
                'redirect_page_id' => array(
                    'title' => __('Return Page', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'select',
                    'options' => $this->pb_get_pages('Select Page'),
                    'description' => __('URL of success page', 'woo-payment-gateway-for-piraeus-bank')
                ),
                'pb_installments' => array(
                    'title' => __('Maximum number of installments regardless of the total order amount', 'woo-payment-gateway-for-piraeus-bank'),
                    'type' => 'select',
                    'options' => $this->pb_get_installments('Select Installments'),
                    'description' => __('1 to 24 Installments,1 for one time payment. You must contact Peiraeus Bank first<br /> If you have filled the "Max Number of installments depending on the total order amount", the value of this field will be ignored.', 'woo-payment-gateway-for-piraeus-bank')
                ),
                'pb_installments_variation' => array(
                    'title' => __('Maximum number of installments depending on the total order amount', 'woo-payment-gateway-for-piraeus-bank'),
                    'type'  => 'text',
                    'description' => __('Example 80:2, 160:4, 300:8</br> total order greater or equal to 80 -> allow 2 installments, total order greater or equal to 160 -> allow 4 installments, total order greater or equal to 300 -> allow 8 installments</br> Leave the field blank if you do not want to limit the number of installments depending on the amount of the order.', 'woo-payment-gateway-for-piraeus-bank')
                )
            );
        }

        function pb_get_pages($title = false, $indent = true) {
            $wp_pages = get_pages('sort_column=menu_order');
            $page_list = array();
            if ($title)
                $page_list[] = $title;
            foreach ($wp_pages as $page) {
                $prefix = '';
                // show indented child pages?
                if ($indent) {
                    $has_parent = $page->post_parent;
                    while ($has_parent) {
                        $prefix .= ' - ';
                        $next_page = get_page($has_parent);
                        $has_parent = $next_page->post_parent;
                    }
                }
                // add to page list array array
                $page_list[$page->ID] = $prefix . $page->post_title;
            }
            $page_list[-1] = __('Thank you page', 'woo-payment-gateway-for-piraeus-bank');
            return $page_list;
        }

        function pb_get_installments($title = false, $indent = true) {


            for ($i = 1; $i <= 24; $i++) {
                $installment_list[$i] = $i;
            }
            return $installment_list;
        }

        function payment_fields() {
            global $woocommerce;
            
            //get: order or cart total, to compute max installments number.
            if(absint(get_query_var('order-pay'))) {
                $order_id = absint(get_query_var('order-pay'));
                $order = new WC_Order($order_id);
                $Amount = $order->get_total();
            } else if(!$woocommerce->cart->is_empty()) {
                $Amount = $woocommerce->cart->total;
            }

            if ($description = $this->get_description()) {
                echo wpautop(wptexturize($description));
            }
            $max_installments = $this->pb_installments;
            $installments_variation = $this->pb_installments_variation; 

            if(!empty($installments_variation)) {
                $max_installments = 1; // initialize the max installments 
                if(isset($installments_variation) && !empty($installments_variation)) {
                    $installments_split = explode(',', $installments_variation);
                    foreach($installments_split as $key => $value) {
                        $installment = explode(':', $value);
                        if(is_array($installment) && count($installment) != 2) {
                            // not valid rule for installments
                            continue;
                        }
                        if(!is_numeric($installment[0]) || !is_numeric($installment[1])) {
                            // not valid rule for installments                            
                            continue;
                        }
                        if($Amount >= ($installment[0])) {
                            $max_installments = $installment[1];
                        }
                    }
                }
            }
            
            if ($max_installments > 1) {

                $doseis_field = '<p class="form-row ">
			<label for="' . esc_attr($this->id) . '-card-doseis">' . __('Choose Installments', 'woo-payment-gateway-for-piraeus-bank') . ' <span class="required">*</span></label>
                        <select id="' . esc_attr($this->id) . '-card-doseis" name="' . esc_attr($this->id) . '-card-doseis" class="input-select wc-credit-card-form-card-doseis">
                        ';
                for ($i = 1; $i <= $max_installments; $i++) {
                    $doseis_field .= '<option value="' . $i . '">' . $i . '</option>';
                }
                $doseis_field .= '</select>
		</p>';
                echo $doseis_field;
            }
        }

        /**
         * Generate the  Piraeus Payment button link
         * */
        function generate_piraeusbank_form($order_id) {
            global $woocommerce;
            global $wpdb;

            // $currency = get_woocommerce_currency();
            $locale = get_locale();
            $availableLocales = array (
                'en' => 'en-US',
                'en_US' => 'en-US',
                'en_AU' => 'en-US',
                'en_CA' => 'en-US',
                'en_GB' => 'en-US',
                'en_NZ' => 'en-US',
                'en_ZA' => 'en-US',
                'el' => 'el-GR',
                'ru_RU' => 'ru-RU',
                'de_DE' => 'de-DE',
                'de_DE_formal' => 'de-DE',
                'de_CH' => 'de-DE',
                'de_CH_informal' => 'de-DE'
            );

            if(isset($availableLocales[$locale])) {
               $lang = $availableLocales[$locale];
            } else {
                $lang = 'en-US';
            }

            $order = new WC_Order($order_id);

            if ($this->pb_authorize == "yes") {
                $requestType = '00';
                $ExpirePreauth = '30';
            } else {
                $requestType = '02';
                $ExpirePreauth = '0';
            }
            $installments = 1;
            /* if ($this->pb_installments > 1) {
              $installments = intval($order->get_total() / 30);
              $installments = min($installments, $this->pb_installments);
              } */
            if (method_exists($order, 'get_meta')) {
                $installments = $order->get_meta('_doseis');
                if ($installments == '') {
                    $installments = 1;
                }
            } else {
                $installments = get_post_meta($order_id, '_doseis', 1);
            }
            try {

                if( $this->pb_ProxyHost!=''){

                    if($this->pb_ProxyUsername != '' && $this->pb_ProxyPassword != ''){
                        $soap = new SoapClient("https://paycenter.piraeusbank.gr/services/tickets/issuer.asmx?WSDL",
                        array(
                            'proxy_host'     => $this->pb_ProxyHost,
                            'proxy_port'     => intval($this->pb_ProxyPort),
                            'proxy_login'    => $this->pb_ProxyUsername,
                            'proxy_password' => $this->pb_ProxyPassword
                            )
                        );
                    }
                    else{
                        $soap = new SoapClient("https://paycenter.piraeusbank.gr/services/tickets/issuer.asmx?WSDL",
                        array(
                            'proxy_host'     => $this->pb_ProxyHost,
                            'proxy_port'     => intval($this->pb_ProxyPort)
                            )
                        );
                    }
                }
                else{
                    $soap = new SoapClient("https://paycenter.piraeusbank.gr/services/tickets/issuer.asmx?WSDL");
                }
                $ticketRequest = array(
                    'Username' => $this->pb_Username,
                    'Password' => hash('md5', $this->pb_Password),
                    'MerchantId' => $this->pb_PayMerchantId,
                    'PosId' => $this->pb_PosId,
                    'AcquirerId' => $this->pb_AcquirerId,
                    'MerchantReference' => $order_id,
                    'RequestType' => $requestType,
                    'ExpirePreauth' => $ExpirePreauth,
                    'Amount' => $order->get_total(),
                    'CurrencyCode' => '978',
                    'Installments' => $installments,
                    'Bnpl' => '0',
                    'Parameters' => ''
                );
                $xml = array(
                    'Request' => $ticketRequest
                );

                $oResult = $soap->IssueNewTicket($xml);

                if ($oResult->IssueNewTicketResult->ResultCode == 0) {

                    //  store TranTicket in table
                    // $wpdb->delete($wpdb->prefix . 'piraeusbank_transactions', array('merch_ref' => $order_id));
                    $wpdb->insert($wpdb->prefix . 'piraeusbank_transactions', array('trans_ticket' => $oResult->IssueNewTicketResult->TranTicket, 'merch_ref' => $order_id, 'timestamp' => current_time('mysql', 1)));

                    //redirect to payment

                    wc_enqueue_js('
				$.blockUI({
						message: "' . esc_js(__('Thank you for your order. We are now redirecting you to Piraeus Bank to make payment.', 'woo-payment-gateway-for-piraeus-bank')) . '",
						baseZ: 99999,
						overlayCSS:
						{
							background: "#fff",
							opacity: 0.6
						},
						css: {
							padding:        "20px",
							zindex:         "9999999",
							textAlign:      "center",
							color:          "#555",
							border:         "3px solid #aaa",
							backgroundColor:"#fff",
							cursor:         "wait",
							lineHeight:		"24px",
						}
					});
				jQuery("#submit_pb_payment_form").click();
			');

                  //  $LanCode = "el-GR";
                    $LanCode = $lang;
                    /*
                      Other available Language codes
                      en-US: English
                      ru-RU: Russian
                      de-DE: German
                     */

                    return '<form action="' . esc_url("https://paycenter.piraeusbank.gr/redirection/pay.aspx") . '" method="post" id="pb_payment_form" target="_top">

						<input type="hidden" id="AcquirerId" name="AcquirerId" value="' . esc_attr($this->pb_AcquirerId) . '"/>
						<input type="hidden" id="MerchantId" name="MerchantId" value="' . esc_attr($this->pb_PayMerchantId) . '"/>
						<input type="hidden" id="PosID" name="PosID" value="' . esc_attr($this->pb_PosId) . '"/>
						<input type="hidden" id="User" name="User" value="' . esc_attr($this->pb_Username) . '"/>
						<input type="hidden" id="LanguageCode"  name="LanguageCode" value="' . $LanCode . '"/>
						<input type="hidden" id="MerchantReference" name="MerchantReference"  value="' . esc_attr($order_id) . '"/>
					<!-- Button Fallback -->
					<div class="payment_buttons">
						<input type="submit" class="button alt" id="submit_pb_payment_form" value="' . __('Pay via Pireaus Bank', 'woo-payment-gateway-for-piraeus-bank') . '" /> <a class="button cancel" href="' . esc_url($order->get_cancel_order_url()) . '">' . __('Cancel order &amp; restore cart', 'woo-payment-gateway-for-piraeus-bank') . '</a>

					</div>
					<script type="text/javascript">
					jQuery(".payment_buttons").hide();
					</script>
				</form>';
                } else {
                    echo __('An error occured, please contact the Administrator', 'woo-payment-gateway-for-piraeus-bank');
                    echo ('Result code is ' . $oResult->IssueNewTicketResult->ResultCode);
                    $order->add_order_note(__('Error' . $oResult->IssueNewTicketResult->ResultCode.':'.$oResult->IssueNewTicketResult->ResultDescription, ''));
                }
            } catch (SoapFault $fault) {
                $order->add_order_note(__('Error' . $fault, ''));
                echo __('Error' . $fault, '');
            }
        }

        /**
         * Process the payment and return the result
         * */
        function process_payment($order_id) {
            /*
              get_permalink was used instead of $order->get_checkout_payment_url in redirect in order to have a fixed checkout page to provide to Piraeus Bank
             */

            $order = new WC_Order($order_id);
            $doseis = intval($_POST[esc_attr($this->id) . '-card-doseis']);
            if ($doseis > 0) {
                $this->generic_add_meta($order_id, '_doseis', $doseis);
            }

            return array(
                'result' => 'success',
                'redirect' => add_query_arg('order-pay', $order->get_id(), add_query_arg('key', $order->get_order_key(), wc_get_page_permalink('checkout')))
                //'redirect' => add_query_arg('order', $order->get_id(), add_query_arg('key', $order->get_order_key(), get_permalink(wc_get_page_id('checkout')).'order-pay/'.$order->get_id()))
            );
        }

        /**
         * Output for the order received page.
         * */
        function receipt_page($order) {
            echo '<p>' . __('Thank you - your order is now pending payment. You should be automatically redirected to Peiraeus Paycenter to make payment.', 'woo-payment-gateway-for-piraeus-bank') . '</p>';
            echo $this->generate_piraeusbank_form($order);
        }

        /**
         * Verify a successful Payment!
         * */
        function check_piraeusbank_response() {


            global $woocommerce;
            global $wpdb;

            if (isset($_REQUEST['peiraeus']) && ($_REQUEST['peiraeus'] == 'success')) {

                $ResultCode = $_REQUEST['ResultCode'];
                $order_id = $_REQUEST['MerchantReference'];
                $order = new WC_Order($order_id);

                if ($ResultCode != 0) {
                    $message = __('A technical problem occured. <br />The transaction wasn\'t successful, payment wasn\'t received.', 'woo-payment-gateway-for-piraeus-bank');
                    $message_type = 'error';
                    $pb_message = array(
                        'message' => $message,
                        'message_type' => $message_type
                    );
                    $this->generic_add_meta($order_id, '_piraeusbank_message', $pb_message);
                    $this->generic_add_meta($order_id, '_piraeusbank_message_debug', $pb_message);
                    wc_add_notice(__('Payment error:', 'woo-payment-gateway-for-piraeus-bank') . $message, $message_type);
                    //Update the order status
                    $order->update_status('failed', '');
                    $checkout_url = $woocommerce->cart->get_checkout_url();
                    wp_redirect($checkout_url);
                    exit;
                }

                $ResponseCode = $_REQUEST['ResponseCode'];
                $StatusFlag = $_REQUEST['StatusFlag'];
                $HashKey = $_REQUEST['HashKey'];
                $SupportReferenceID = $_REQUEST['SupportReferenceID'];
                $ApprovalCode = $_REQUEST['ApprovalCode'];
                $Parameters = $_REQUEST['Parameters'];
                $AuthStatus = $_REQUEST['AuthStatus'];
                $PackageNo = $_REQUEST['PackageNo'];




                $ttquery = 'SELECT trans_ticket
			FROM `' . $wpdb->prefix . 'piraeusbank_transactions`
			WHERE `merch_ref` = ' . $order_id . '	;';
                $tt = $wpdb->get_results($ttquery);

                $hasHashKeyNotMatched = true;

                    foreach($tt as $transaction) {

                        if(!$hasHashKeyNotMatched)
                            break;

                        $transticket = $transaction->trans_ticket;

                        $stcon = $transticket . $this->pb_PosId . $this->pb_AcquirerId . $order_id . $ApprovalCode . $Parameters . $ResponseCode . $SupportReferenceID . $AuthStatus . $PackageNo . $StatusFlag;

                        $conhash = strtoupper(hash('sha256', $stcon));

                        // $newHashKey
                        $stconHmac = $transticket . ';' . $this->pb_PosId . ';' .  $this->pb_AcquirerId . ';' .  $order_id . ';' .  $ApprovalCode . ';' .  $Parameters . ';' .  $ResponseCode . ';' .  $SupportReferenceID . ';' .  $AuthStatus . ';' .  $PackageNo . ';' .  $StatusFlag;
                        $consHashHmac = strtoupper(hash_hmac('sha256', $stconHmac, $transticket, false));

                            if($consHashHmac != $HashKey && $conhash != $HashKey) {
                                continue;
                            } else {
                                $hasHashKeyNotMatched= false;
                            }
                    }


                if($hasHashKeyNotMatched) {

                    $message = __('Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment wasn\'t received.', 'woo-payment-gateway-for-piraeus-bank');
                    $message_type = 'error';
                    $pb_message = array(
                        'message' => $message,
                        'message_type' => $message_type
                    );
                    $this->generic_add_meta($order_id, '_piraeusbank_message', $pb_message);
                    $this->generic_add_meta($order_id, '_piraeusbank_message_debug', array($pb_message, $consHashHmac . '!=' . $HashKey));
                    //wc_add_notice(__('Payment error:', 'woo-payment-gateway-for-piraeus-bank') . $message, $message_type);
                    //Update the order status
                    $order->update_status('failed', '');
                    $checkout_url = $woocommerce->cart->get_checkout_url();
                    wp_redirect($checkout_url);
                    exit;
                 }
                else {

                    if ($ResponseCode == 0 || $ResponseCode == 8 || $ResponseCode == 10 || $ResponseCode == 16) {

                        if ($order->get_status() == 'processing') {

                            $order->add_order_note(__('Payment Via Peiraeus Bank<br />Transaction ID: ', 'woo-payment-gateway-for-piraeus-bank') . $SupportReferenceID);

                            //Add customer order note
                            $order->add_order_note(__('Payment Received.<br />Your order is currently being processed.<br />We will be shipping your order to you soon.<br />Peiraeus Bank ID: ', 'woo-payment-gateway-for-piraeus-bank') . $SupportReferenceID, 1);

                            // Reduce stock levels
                            $order->reduce_order_stock();

                            // Empty cart
                            WC()->cart->empty_cart();

                            $message = __('Thank you for shopping with us.<br />Your transaction was successful, payment was received.<br />Your order is currently being processed.', 'woo-payment-gateway-for-piraeus-bank');
                            $message_type = 'success';
                            //wc_add_notice( $message, $message_type );
                        } else {

                            if ($order->has_downloadable_item()) {

                                 // check if the order has only downloadable items
                                 $hasOnlyDownloadable = true; 
                                 foreach ($order->get_items() as $key => $order_prod) {
                                     $p = $order_prod->get_product();
                                     if($p->is_downloadable() == false && $p->is_virtual() == false) {
                                         $hasOnlyDownloadable = false; 
                                     }
                                   }

                                   if($hasOnlyDownloadable) {
                                        //Update order status
                                        $order->update_status('completed', __('Payment received, your order is now complete.', 'woo-payment-gateway-for-piraeus-bank'));

                                        //Add admin order note
                                        $order->add_order_note(__('Payment Via Peiraeus Bank<br />Transaction ID: ', 'woo-payment-gateway-for-piraeus-bank') . $SupportReferenceID);

                                        //Add customer order note
                                        $order->add_order_note(__('Payment Received.<br />Your order is now complete.<br />Peiraeus Transaction ID: ', 'woo-payment-gateway-for-piraeus-bank') . $SupportReferenceID, 1);

                                        $message = __('Thank you for shopping with us.<br />Your transaction was successful, payment was received.<br />Your order is now complete.', 'woo-payment-gateway-for-piraeus-bank');
                                        $message_type = 'success';
                                   } else {
                                        //Update order status
                                        $order->update_status('processing', __('Payment received, your order is currently being processed.', 'woo-payment-gateway-for-piraeus-bank'));

                                        //Add admin order note
                                        $order->add_order_note(__('Payment Via Peiraeus Bank<br />Transaction ID: ', 'woo-payment-gateway-for-piraeus-bank') . $SupportReferenceID);

                                        //Add customer order note
                                        $order->add_order_note(__('Payment Received.<br />Your order is currently being processed.<br />We will be shipping your order to you soon.<br />Peiraeus Bank ID: ', 'woo-payment-gateway-for-piraeus-bank') . $SupportReferenceID, 1);

                                        $message = __('Thank you for shopping with us.<br />Your transaction was successful, payment was received.<br />Your order is currently being processed.', 'woo-payment-gateway-for-piraeus-bank');
                                        $message_type = 'success';
                                   }

                            } else {

                                //Update order status
                                $order->update_status('processing', __('Payment received, your order is currently being processed.', 'woo-payment-gateway-for-piraeus-bank'));

                                //Add admin order note
                                $order->add_order_note(__('Payment Via Peiraeus Bank<br />Transaction ID: ', 'woo-payment-gateway-for-piraeus-bank') . $SupportReferenceID);

                                //Add customer order note
                                $order->add_order_note(__('Payment Received.<br />Your order is currently being processed.<br />We will be shipping your order to you soon.<br />Peiraeus Bank ID: ', 'woo-payment-gateway-for-piraeus-bank') . $SupportReferenceID, 1);

                                $message = __('Thank you for shopping with us.<br />Your transaction was successful, payment was received.<br />Your order is currently being processed.', 'woo-payment-gateway-for-piraeus-bank');
                                $message_type = 'success';
                            }

                            $pb_message = array(
                                'message' => $message,
                                'message_type' => $message_type
                            );

                            $this->generic_add_meta($order_id, '_piraeusbank_message', $pb_message);
                            $this->generic_add_meta($order_id, '_piraeusbank_message_debug', $pb_message);
                            // Reduce stock levels
                            $order->reduce_order_stock();

                            // Empty cart
                            WC()->cart->empty_cart();
                        }
                    } else if ($ResponseCode == 11) {

                        $message = __('Thank you for shopping with us.<br />Your transaction was previously received.<br />', 'woo-payment-gateway-for-piraeus-bank');
                        $message_type = 'success';


                        $pb_message = array(
                            'message' => $message,
                            'message_type' => $message_type
                        );
                        $this->generic_add_meta($order_id, '_piraeusbank_message', $pb_message);
                        $this->generic_add_meta($order_id, '_piraeusbank_message_debug', $pb_message);
                    } else { //Failed Response codes

                        $message = __('Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment wasn\'t received.', 'woo-payment-gateway-for-piraeus-bank');
                        $message_type = 'error';
                        $pb_message = array(
                            'message' => $message,
                            'message_type' => $message_type
                        );
                        $this->generic_add_meta($order_id, '_piraeusbank_message', $pb_message);
                        $this->generic_add_meta($order_id, '_piraeusbank_message_debug', $pb_message);
                        //Update the order status
                        $order->update_status('failed', '');
                    }
                }
            }
            if (isset($_REQUEST['peiraeus']) && ($_REQUEST['peiraeus'] == 'fail')) {

                if (isset($_REQUEST['MerchantReference'])) {
                    $order_id = $_REQUEST['MerchantReference'];
                    $order = new WC_Order($order_id);
                    $message = __('Thank you for shopping with us. <br />However, the transaction wasn\'t successful, payment wasn\'t received.', 'woo-payment-gateway-for-piraeus-bank');
                    $message_type = 'error';

                    $transaction_id = $_REQUEST['SupportReferenceID'];

                    //Add Customer Order Note
                    $order->add_order_note($message . '<br />Piraeus Bank Transaction ID: ' . $transaction_id, 1);

                    //Add Admin Order Note
                    $order->add_order_note($message . '<br />Piraeus Bank Transaction ID: ' . $transaction_id);


                    //Update the order status
                    $order->update_status('failed', '');

                    $pb_message = array(
                        'message' => $message,
                        'message_type' => $message_type
                    );

                    $this->generic_add_meta($order_id, '_piraeusbank_message', $pb_message);
                    $this->generic_add_meta($order_id, '_piraeusbank_message_debug', $pb_message);
                }
            }
            if (isset($_REQUEST['peiraeus']) && ($_REQUEST['peiraeus'] == 'cancel')) {


                $checkout_url = $woocommerce->cart->get_checkout_url();
                wp_redirect($checkout_url);
                exit;
            }
            if ($this->redirect_page_id == "-1") {
                $redirect_url = $this->get_return_url($order);
            } else {
                $redirect_url = ($this->redirect_page_id == "" || $this->redirect_page_id == 0) ? get_site_url() . "/" : get_permalink($this->redirect_page_id);
                //For wooCoomerce 2.0
                $redirect_url = add_query_arg(array('msg' => urlencode($this->msg['message']), 'type' => $this->msg['class']), $redirect_url);
            }
            wp_redirect($redirect_url);

            exit;
        }

        function generic_add_meta($orderid, $key, $value) {
            $order = new WC_Order($orderid);
            if (method_exists($order, 'add_meta_data') && method_exists($order, 'save_meta_data')) {
                $order->add_meta_data($key, $value, true);
                $order->save_meta_data();
            } else {
                update_post_meta($orderid, $key, $value);
            }
        }

    }

    function piraeusbank_message() {
        $order_id = absint(get_query_var('order-received'));
        $order = new WC_Order($order_id);
        if (method_exists($order, 'get_payment_method')) {
            $payment_method = $order->get_payment_method();
        } else {
            $payment_method = $order->payment_method;
        }

        if (is_order_received_page() && ( 'piraeusbank_gateway' == $payment_method )) {

            $piraeusbank_message = '';
            if (method_exists($order, 'get_meta')) {
                $piraeusbank_message = $order->get_meta('_piraeusbank_message', true);
            } else {
                $piraeusbank_message = get_post_meta($order_id, '_piraeusbank_message', true);
            }
            if (!empty($piraeusbank_message)) {
                $message = $piraeusbank_message['message'];
                $message_type = $piraeusbank_message['message_type'];
                if (method_exists($order, 'delete_meta_data')) {
                    $order->delete_meta_data('_piraeusbank_message');
                    $order->save_meta_data();
                } else {
                    delete_post_meta($order_id, '_piraeusbank_message');
                }

                wc_add_notice($message, $message_type);
            }
        }
    }

    add_action('wp', 'piraeusbank_message');

    /**
     * Add Piraeus Bank Gateway to WC
     * */
    function woocommerce_add_piraeusbank_gateway($methods) {
        $methods[] = 'WC_Piraeusbank_Gateway';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_piraeusbank_gateway');





    /**
     * Add Settings link to the plugin entry in the plugins menu for WC below 2.1
     * */
    if (version_compare(WOOCOMMERCE_VERSION, "2.1") <= 0) {

        add_filter('plugin_action_links', 'piraeusbank_plugin_action_links', 10, 2);

        function piraeusbank_plugin_action_links($links, $file) {
            static $this_plugin;

            if (!$this_plugin) {
                $this_plugin = plugin_basename(__FILE__);
            }

            if ($file == $this_plugin) {
                $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=woocommerce_settings&tab=payment_gateways&section=WC_piraeusbank_Gateway">Settings</a>';
                array_unshift($links, $settings_link);
            }
            return $links;
        }

    }
    /**
     * Add Settings link to the plugin entry in the plugins menu for WC 2.1 and above
     * */ else {
        add_filter('plugin_action_links', 'piraeusbank_plugin_action_links', 10, 2);

        function piraeusbank_plugin_action_links($links, $file) {
            static $this_plugin;

            if (!$this_plugin) {
                $this_plugin = plugin_basename(__FILE__);
            }

            if ($file == $this_plugin) {
                $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=wc-settings&tab=checkout&section=WC_Piraeusbank_Gateway">Settings</a>';
                array_unshift($links, $settings_link);
            }
            return $links;
        }

    }
}
