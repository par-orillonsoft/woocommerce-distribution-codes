<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Distribution_Codes_Order {

	public function __construct() {

		//attach codes to order on payment
		add_action( 'woocommerce_payment_complete', array( $this, 'give_distribution_code' ) );
		add_action( 'woocommerce_order_status_on-hold_to_processing', array( $this, 'give_distribution_code' ) );
		add_action( 'woocommerce_order_status_on-hold_to_completed',  array( $this, 'give_distribution_code' ) );
		add_action( 'woocommerce_order_status_failed_to_processing', array( $this, 'give_distribution_code' ) );
		add_action( 'woocommerce_order_status_failed_to_completed',  array( $this, 'give_distribution_code' ) );

		//attach code to order complete page and view order page
		add_action( 'woocommerce_thankyou', array( $this, 'show_codes_in_order_complete' ), 1 );
		add_action( 'woocommerce_view_order', array( $this, 'show_codes_in_order_complete' ), 1 );

		//attach code to emails
		add_action( 'woocommerce_email_before_order_table', array( $this, 'show_codes_in_email' ), 1 );



	}

	public function give_distribution_code( $order ) {

		if ( ! is_object( $order ) )
			$order = new WC_Order( $order );

		$give_codes = array();
		$give_codes_note = '';

		$item_count = count($order->get_items());

		foreach ( $order->get_items() as $key => $product ) {



			$product_id = $product['product_id'];
			$codes = unserialize(get_post_meta($product_id, '_wc_distribution_codes', true));
			if( empty($codes) ){
				continue;
			}
			else{
				$qty = $product['qty'];
				while($qty > 0){
					$codes = unserialize(get_post_meta($product_id, '_wc_distribution_codes', true));
					$code_count = count($codes);
					$code = $codes[$code_count-1];
					unset($codes[$code_count-1]);
					if(empty($codes)){
						update_post_meta($product_id, '_wc_distribution_codes', '');
					}
					else{
						$new_codes = serialize($codes);
						update_post_meta($product_id, '_wc_distribution_codes', $new_codes);
					}
					$give_codes[$product_id][$qty] = $code;

					$give_codes_note .= $code.' ';
					$qty--;
				}
			}
		}

		if($give_codes_note != ''){
			// set order meta
			update_post_meta( $order->id, '_wc_distribution_codes', $give_codes );

		// add order note
			$order->add_order_note('Customer recieved codes: '.$give_codes_note);
		}
	}



	public function show_codes_in_order_complete( $order_id)
	{
		$order = new WC_Order( $order_id );

		$codes = $order->order_custom_fields['_wc_distribution_codes'][0];

		$codes = unserialize($codes);

		if($codes){
			echo '<h2>Your Codes:</h2>';

			foreach($codes as $product_id => $qty){
				foreach($qty as $key => $code){
					$product_name = get_the_title($product_id);
					$code_name = get_post_meta($product_id, '_wc_distribution_codes_message', true);
					echo '<em>'.$product_name.'</em> - '.$code_name.' : <strong>'.$code.'</strong><br>';
				}
			}
			echo '<br><br>';

		}
	}

	public function show_codes_in_email( $order )
	{
		$order = new WC_Order( $order );

		$codes = $order->order_custom_fields['_wc_distribution_codes'][0];

		$codes = unserialize($codes);

		if($codes){
			echo '<h2>Your Codes:</h2>';

			foreach($codes as $product_id => $qty){
				foreach($qty as $key => $code){
					$product_name = get_the_title($product_id);
					$code_name = get_post_meta($product_id, '_wc_distribution_codes_message', true);
					echo '<em>'.$product_name.'</em> - '.$code_name.' : <strong>'.$code.'</strong><br>';
				}
			}
			echo '<br><br>';

		}
	}

}
