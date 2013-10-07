<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Distribution_Codes_Product_Admin {


	public function __construct() {

		/** Simple Subscription hooks */

	    // save 'Distribution Codes' field for subscription products
		add_action( 'woocommerce_process_product_meta_subscription',   array( $this, 'save_simple_product_fields' ) );

		/** Variable Subscription hooks */

	    // save the 'Distribution Codes' field for variable subscription products
		add_action( 'woocommerce_save_product_subscription_variation', array( $this, 'save_variable_product_fields' ) );

		/** Simple Product hooks */

		// add 'Distribution Codes' field to simple product general tab
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'render_simple_product_fields' ) );

		// save 'Distribution Codes' field for simple products
		add_action( 'woocommerce_process_product_meta_simple',   array( $this, 'save_simple_product_fields' ) );

	}


	public function render_simple_product_fields() {

		global $post;

		$codesout = '';
		$codes = unserialize(get_post_meta( $post->ID, '_wc_distribution_codes', true));
		$codes_count = count($codes);
		foreach($codes as $code){
			$codes_count --;
			if($codes_count == 0){
				$codesout .= $code;
			}
			else{
				$codesout .= $code."\n";
			}
		}

		echo '<div class="options_group">';

		woocommerce_wp_text_input( array(
			'id'                => '_wc_distribution_codes_message',
			'class'             => 'long',
			'label'             => __( 'Distribution Codes Description', WC_Distribution_Codes::TEXT_DOMAIN ),
			'description'       => __( 'This message will display in the product summary. Use this space to explain the code that customers will recieve.', WC_Distribution_Codes::TEXT_DOMAIN ),
			'desc_tip'          => true,
			'placeholder'		=> "10% off coupon code!"
			)
		);

		woocommerce_wp_textarea_input( array(
			'id'                => '_wc_distribution_codes',
			'class'             => 'textarea',
			'label'             => __( 'Distribution Codes', WC_Distribution_Codes::TEXT_DOMAIN ),
			'description'       => __( 'These codes will be distributed with every purchase of this product.', WC_Distribution_Codes::TEXT_DOMAIN ),
			'desc_tip'          => true,
			'value'				=> $codesout,
			'placeholder'		=> "One code per line"
			)
		);

		echo '</div>';
	}

	public function save_simple_product_fields( $post_id ) {

		$codes = trim($_POST['_wc_distribution_codes']);
		$codes = explode("\n",$codes);


		if ( '' !== $_POST['_wc_distribution_codes'] )
			update_post_meta( $post_id, '_wc_distribution_codes', serialize($codes) );
		else
			delete_post_meta( $post_id, '_wc_distribution_codes' );

		if ( '' !== $_POST['_wc_distribution_codes_message'] )
			update_post_meta( $post_id, '_wc_distribution_codes_message', $_POST['_wc_distribution_codes_message'] );
		else
			delete_post_meta( $post_id, '_wc_distribution_codes_message' );
	}



} // end \WC_Distribution_Codes_Admin class
