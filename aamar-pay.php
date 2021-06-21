<?php

/**
 * @package Amar_Pay_By_Xeronce
 * @version 1.0.0
 */
/*
Plugin Name: Amar Pay - Donation Gateway Plugin by XERONCE
Plugin URI: https://xeronce.com
Description: Plugin for integrate givewp and amar-pay donation/payment gateway to collect payment/donation from Bangladesh.
Author: Tazim Hossain
Version: 1.0.0
Author URI: https://xeronce.com
*/


// Give Plugin Dependency Check
register_activation_hook( __FILE__, 'is_base_plugins_active' );
function is_base_plugins_active() {
	if (! is_plugin_active( GIVE_PLUGIN_FILE ) ) {
		wp_die( 'Sorry, but this plugin requires the "Give" Plugin to be installed and active.' );
	}
}

// Required Urls
define('PAYMENT_URL', 'https://sandbox.aamarpay.com/index.php');
//define('PAYMENT_URL', 'https://secure.aamarpay.com/index.php');
define( 'aamarpay_IMG', WP_PLUGIN_URL . "/" . plugin_basename( dirname( __FILE__ ) ) . '/assets/img' );



function amarpay_for_give_register_payment_method( $gateways ) {
	$gateways['amarpay'] = array(
		'admin_label'    => __( 'Amar Pay - Credit Card', 'amarpay-for-give' ),
		'checkout_label' => __( 'Credit Card', 'amarpay-for-give' ),
	);
	return $gateways;
}
add_filter( 'give_payment_gateways', 'amarpay_for_give_register_payment_method' );



function amarpay_for_give_register_payment_gateway_sections( $sections ) {
	$sections['amarpay-settings'] = __( 'AmarPay', 'amarpay-for-give' );
	return $sections;
}
add_filter( 'give_get_sections_gateways', 'amarpay_for_give_register_payment_gateway_sections' );



function amarpay_for_give_register_payment_gateway_setting_fields( $settings ) {

	switch ( give_get_current_setting_section() ) {
		case 'amarpay-settings':
			$settings   = array(
				array(
					'id'   => 'give_title_amarpay',
					'type' => 'title',
				),
			);
			$settings[] = array(
				'name' => __( 'Signature Key', 'give-square' ),
				'desc' => __( 'Enter your Signature Key, given from AamarPay.', 'amarpay-for-give' ),
				'id'   => 'amarpay_for_give_amarpay_signature_key',
				'type' => 'text',
			);
			$settings[] = array(
				'name' => __( 'Merchant Id', 'give-square' ),
				'desc' => __( 'Enter your Merchant Id given from AamarPay.', 'amarpay-for-give' ),
				'id'   => 'amarpay_for_give_amarpay_merchant_id',
				'type' => 'text',
			);
			$settings[] = array(
				'id'   => 'give_title_amarpay',
				'type' => 'sectionend',
			);
			break;

	} // End switch().

	return $settings;
}
add_filter( 'give_get_settings_gateways', 'amarpay_for_give_register_payment_gateway_setting_fields' );



function give_amarpay_billing_fields( $form_id ) {
	printf(
		'
		<fieldset class="no-fields">
			<div style="display: flex; justify-content: center; margin-top: 20px;">
				<img src="' . aamarpay_IMG . '/aamar-pay-logo.png" height="24px" />
			</div>
			<p style="text-align: center;"><b>%1$s</b></p>
			<p style="text-align: center;">
				<b>%2$s</b> %3$s
			</p>
		</fieldset>
	',
		__( 'Make your donation quickly and securely with AamarPay', 'give' ),
		__( 'How it works:', 'give' ),
		__( 'You will be redirected to AamarPay to pay using your selected method, or with a credit or debit card and mobile banking. You will then be brought back to this page to view your receipt.', 'give' )
	);

}
add_action( 'give_amarpay_cc_form', 'give_amarpay_billing_fields' );


/**
 * Set params as form input and auto submit the form to redirect gateway website
 * @param $donation_data
 **/
// function send_post_data_to_payment_website( $donation_data ) {
// 	echo '<p><strong>' . __( 'Thank you for your order.', 'woo_aamarpay' ) . '</strong><br/>' . __( 'The payment page will open soon.', 'woo_aamarpay' ) . '</p>';
// 	echo generate_post_form($donation_data);
// } //END-receipt_page



// /**
//  * Generate Form
//  **/
// function generate_post_form( $transaction_data) {
// 	$redirect_url        = get_site_url() . "/?page_id=5";
// 	$gateway_data_args       = array(
// 		"store_id"      => $transaction_data['store_id'],
// 		"tran_id"       => '1111111',
// 		"signature_key" => $transaction_data['signature_key'],
// 		"success_url"   => $redirect_url,
// 		"fail_url"      => $redirect_url,
// 		"cancel_url"    => $redirect_url,
// 		"amount"        => 22,
// 		"currency"      => 'USD',
// 		"desc"          => "no data",
// 		"cus_name"      => 'test name',
// 		"cus_email"     => 'test@test.com',
// 		"cus_add1"      => 'add1',
// 		"cus_add2"      => 'add2',
// 		"cus_city"      => 'city',
// 		"cus_state"     => 'state',
// 		"cus_postcode"  => 1234,
// 		"cus_country"   => 'BD',
// 		"cus_phone"     => "123456789",
// 	);
// 	$gateway_data_args_array = array();
// 	foreach ( $gateway_data_args as $key => $value ) {
// 		$gateway_data_args_array[] = "<input type='hidden' name='$key' value='$value'/>";
// 	}

// 	return '	<form action="' . PAYMENT_URL . '" method="post">
//   				' . implode( '', $gateway_data_args_array ) . '
// 				    <input type="submit" class="button-alt" id="submit_generated_form" value="' . __( 'Pay via AamarPay', 'give-square' ) . '" /> <a class="button cancel" href="/">' . __( 'Cancel order &amp; restore cart', 'give-square' ) . '</a>
// 					<script type="text/javascript">
// 					    console.log("Form Called!");
// 					    jQuery(function(){
// 					        $("submit_generated_form").click();
// 					    });
// 					</script>
// 				</form>';

// } //END-generate_aamarpay_form



function amarpay_for_give_process_amarpay_donation( $posted_data ) {

	give_clear_errors();
	$errors = give_get_errors();
	if ( ! $errors ) {

		$form_id         = intval( $posted_data['post_data']['give-form-id'] );
		$price_id        = ! empty( $posted_data['post_data']['give-price-id'] ) ? $posted_data['post_data']['give-price-id'] : 0;
		$donation_amount = ! empty( $posted_data['price'] ) ? $posted_data['price'] : 0;
		$redirect_to_url = ! empty( $data['post_data']['give-current-url'] ) ? $data['post_data']['give-current-url'] : site_url();
		$purchase_key    = $posted_data['purchase_key'];

		$donation_data = array(
			'price'           => $donation_amount,
			'give_form_title' => $posted_data['post_data']['give-form-title'],
			'give_form_id'    => $form_id,
			'give_price_id'   => $price_id,
			'date'            => $posted_data['date'],
			'user_email'      => $posted_data['user_email'],
			'purchase_key'    => $purchase_key,
			'currency'        => give_get_currency( $form_id ),
			'user_info'       => $posted_data['user_info'],
			'status'          => 'pending',
			'gateway'         => 'amarpay',
		);


		$donation_id = give_insert_payment( $donation_data );
		if ( ! $donation_id ) {
			give_record_gateway_error(
				__( 'AmarPay Error', 'amarpay-for-give' ),
				sprintf(
					__( 'Unable to create a pending donation with Give.', 'amarpay-for-give' )
				)
			);
			give_send_back_to_checkout( '?payment-mode=amarpay' );
			return;
		}

		// Do the actual payment processing using the custom payment gateway API. To access the GiveWP settings, use give_get_option()
		// as a reference, this pulls the API key entered above: give_get_option('amarpay_for_give_amarpay_api_key')



		// Redirect to show loading area to trigger redirectToCheckout client side.
		wp_safe_redirect(
			add_query_arg(
				array(
					'action'      => 'process_amarpay',
					'session'     => $purchase_key,
				),
				$redirect_to_url
			)
		);

		// Ensure that donation stops from here and redirect to checkout page.
		give_die();

		// Make required data array
		// $donation_amount  = ! empty( $posted_data['price'] ) ? $posted_data['price'] : 0;
		// $transaction_data = array(
		// 	"store_id"      => give_get_option( 'amarpay_for_give_amarpay_merchant_id' ),
		// 	"tran_id"       => $donation_id,
		// 	"signature_key" => give_get_option( 'amarpay_for_give_amarpay_signature_key' ),
		// 	"success_url"   => '-',
		// 	"fail_url"      => '-',
		// 	"cancel_url"    => '-',
		// 	"amount"        => $donation_amount,
		// 	"currency"      => $donation_data['currency'],
		// 	"desc"          => "no data",
		// 	"cus_name"      => $donation_data['user_info']['first_name'] . " " . $donation_data['user_info']['last_name'],
		// 	"cus_email"     => $donation_data['user_email'],
		// 	"cus_add1"      => $donation_data['user_info']['address']['line1'],
		// 	"cus_add2"      => $donation_data['user_info']['address']['line2'],
		// 	"cus_city"      => $donation_data['user_info']['address']['city'],
		// 	"cus_state"     => $donation_data['user_info']['address']['state'],
		// 	"cus_postcode"  => $donation_data['user_info']['address']['zip'],
		// 	"cus_country"   => $donation_data['user_info']['address']['country'],
		// 	"cus_phone"     => "123456789",
		// );
		
		// send_post_data_to_payment_website($transaction_data);




//		if($transaction_response['response']['code'] === 200){
//			give_update_payment_status( $donation_id, 'publish' );
//			give_send_to_success_page();
//		} else {
//			give_send_back_to_checkout( '?payment-mode=amarpay' );
//			global $error;
//			$error = new WP_Error('', 'Try again!');
//		}
	} else {
		give_send_back_to_checkout( '?payment-mode=amarpay' );
	} // End if().
}
add_action( 'give_gateway_amarpay', 'amarpay_for_give_process_amarpay_donation' );


function amarpay_redirect_to_checkout() {
	$get_data = give_clean( $_GET );

	// Bailout, if not processing Amarpay donations.
	if ( ! empty( $get_data['action'] ) && 'process_amarpay' !== $get_data['action'] ) {
		return;
	}

	$key = ! empty( $get_data['session'] ) ? $get_data['session'] : '';
	$donation_id = give_get_donation_id_by_key( $key );

	// Bailout, if donation id doesn't exist or incorrect.
	if ( ! $donation_id ) {
		return;
	}
	
	
	$gateway_data_args       = array(
		"store_id"      => give_get_option( 'amarpay_for_give_amarpay_merchant_id' ),
		"tran_id"       => $donation_id,
		"signature_key" => give_get_option( 'amarpay_for_give_amarpay_signature_key' ),
		"success_url"   => give_get_success_page_uri(),
		"fail_url"      => give_get_failed_transaction_uri(),
		"cancel_url"    => give_get_success_page_uri(),
		"amount"        => give_donation_amount( $donation_id ),
		"currency"      => give_get_currency( $donation_id ),
		"desc"          => "no data",
		"cus_name"      => 'test name',
		"cus_email"     => 'test@test.com',
		"cus_add1"      => 'add1',
		"cus_add2"      => 'add2',
		"cus_city"      => 'city',
		"cus_state"     => 'state',
		"cus_postcode"  => 1234,
		"cus_country"   => 'BD',
		"cus_phone"     => "123456789",
	);
	$gateway_data_args_array = array();
	foreach ( $gateway_data_args as $key => $value ) {
		$gateway_data_args_array[] = "<input type='hidden' name='$key' value='$value'/>";
	}

	echo '	<form action="' . PAYMENT_URL . '" method="post">
  				' . implode( '', $gateway_data_args_array ) . '
				    <input type="submit" class="button-alt" id="submit_generated_form" value="' . __( 'Pay via AamarPay', 'give-square' ) . '" /> <a class="button cancel" href="/">' . __( 'Cancel order &amp; restore cart', 'give-square' ) . '</a>
					<script type="text/javascript">
					    console.log("Form Called!");
					    jQuery(function(){
					        $("submit_generated_form").click();
					    });
					</script>
				</form>';
}

add_action( 'init', 'amarpay_redirect_to_checkout' );
