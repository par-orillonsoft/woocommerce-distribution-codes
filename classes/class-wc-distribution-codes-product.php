<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class WC_Distribution_Codes_Product {

	public function __construct() {

		// add single product message immediately after product excerpt
		add_action( 'woocommerce_single_product_summary', array( $this, 'render_product_message' ) );

		// add variation message before the price is displayed
		add_filter( 'woocommerce_variation_price_html',      array( $this, 'render_product_message' ), 10, 2 );
		add_filter( 'woocommerce_variation_sale_price_html', array( $this, 'render_product_message' ), 10, 2 );
	}

	public function render_product_message() {
		global $product;

		$message = get_post_meta($product->id, '_wc_distribution_codes_message');
		$message = $message[0];

		// check if exists
		if ( ! $message || $message == '')
			return;

		$message = apply_filters( 'wc_distribution_codes_product_message', $message );

		echo '<div class="wc_distribution_code_message"><strong>Includes Code: </strong>';
		echo $message;
		echo '</div><br>';
	}

}
