<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class WC_Distribution_Codes_Cart {

	function __construct()
	{
		add_filter( 'woocommerce_get_item_data', array( $this, 'display_if_has_code' ), 10, 2 );
		//add_action( 'woocommerce_product_meta_start', array( $this , 'display_distribution_code_status' ), 10, 2 );
	}

	public function display_if_has_code( $values, $cart_item )
	{
		$data = array();
		$product_id = $cart_item['data']->post->ID;

		if(get_post_meta($product_id, '_wc_distribution_codes_message', true)){
			$data[] = array(
			'name'    => 'Includes Code',
			'display' => get_post_meta($product_id, '_wc_distribution_codes_message', true),
			);

		return $data;
		}
	}
}
