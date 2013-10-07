<?php
/**
 * Plugin Name: WooCommerce Distribution Codes
 * Plugin URI:
 * Description: Distribute unique plain text codes or strings
 * Author: Tanner Linsley
 * Author URI: http://tannerlinsley.com
 * Version: 1.0.0
 * Text Domain: wc-distribution-codes
 *
 * Copyright: (c) 2013 Tanner Linsley
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   WC-Distribution-Codes
 * @author    Tanner Linsley
 * @category  Marketing
 * @copyright Copyright (c) 2013, Tanner Linsley
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Required functions
if ( ! function_exists( 'woothemes_queue_update' ) )
	require_once( 'woo-includes/woo-functions.php' );

// Check if WooCommerce is active
if ( ! is_woocommerce_active() )
	return;

$GLOBALS['wc_distribution_codes'] = new WC_Distribution_Codes();


class WC_Distribution_Codes {


	/** plugin version number */
	const VERSION = '1.0.0';

	/** plugin text domain */
	const TEXT_DOMAIN = 'wc-distribution-codes';

	/** @var string the plugin path */
	private $plugin_path;

	/** @var string the plugin url */
	private $plugin_url;

	/** @var \WC_Logger instance */
	private $logger;

	/** @var \WC_Distribution_Codes_Admin admin class */
	private $admin;

	/** @var \WC_Distribution_Codes_Admin product admin class */
	private $product_admin;

	/** @var WP_Admin_Message_Handler admin message handler class */
	public $admin_message_handler;

	/** @var WC_Distribution_Codes_Actions the core actions integration */
	public $actions;


	//Initializes the plugin

	public function __construct() {

		// include required files
		$this->includes();
	}


	//Include required files
	private function includes() {

		// product class
		require( 'classes/class-wc-distribution-codes-product.php' );
		$this->product = new WC_Distribution_Codes_Product();

		// order class
		require( 'classes/class-wc-distribution-codes-order.php' );
		$this->order = new WC_Distribution_Codes_Order();

		// cart class
		require( 'classes/class-wc-distribution-codes-cart.php' );
		$this->cart = new WC_Distribution_Codes_Cart();

		if ( is_admin() )
			$this->admin_includes();
	}


	// Include admin files
	private function admin_includes() {

		// load admin class
		require( 'classes/class-wc-distribution-codes-admin.php' );
		$this->admin = new WC_Distribution_Codes_Admin();

		// load product admin class
		require( 'classes/class-wc-distribution-codes-product-admin.php' );
		$this->product_admin = new WC_Distribution_Codes_Product_Admin();

	}
}



