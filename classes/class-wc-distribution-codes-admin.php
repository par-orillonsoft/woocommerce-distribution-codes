<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load / saves admin settings

class WC_Distribution_Codes_Admin {


	/** @var string settings page ID */
	private $page_id;

	/** @var array points & rewards manage/actions tabs */
	private $tabs;


	//Setup admin class

	public function __construct() {

		$this->tabs = array(
			'global'  => __( 'Global Codes', WC_Distribution_Codes::TEXT_DOMAIN )
			);

		/** General admin hooks */

		// Load WC styles / scripts
		add_filter( 'woocommerce_screen_ids', array( $this, 'load_wc_scripts' ) );

		// add 'Distribution Codes' link under WooCommerce menu
		add_action( 'admin_menu', array( $this, 'add_menu_link' ) );

		// save global codes ajax script
		add_action( 'in_admin_footer',   array( $this, 'save_global_codes' ) );

		// Add the points earned/redeemed for a discount to the edit order page
		add_action( 'woocommerce_admin_order_totals_after_shipping', array( $this, 'render_distribution_codes_info' ) );

	}

	//Add settings/export screen ID to the list of pages for WC to load its JS on
	public function load_wc_scripts( $screen_ids ) {

		// sub-menu page
		$screen_ids[] = 'woocommerce_page_wc_distribution_codes';

		return $screen_ids;
	}



	// Add 'Distribution Codes' sub-menu link under 'WooCommerce' top level menu

	public function add_menu_link() {

		$this->page_id = add_submenu_page(
			'woocommerce',
			__( 'Distribution Codes', WC_Distribution_Codes::TEXT_DOMAIN ),
			__( 'Distribution Codes', WC_Distribution_Codes::TEXT_DOMAIN ),
			'manage_woocommerce',
			'wc_distribution_codes',
			array( $this, 'show_sub_menu_page' )
			);

	}


	/**
	 * Show Points & Rewards Manage/Log page content
	 *
	 * @since 1.0
	 */
	public function show_sub_menu_page() {

		$current_tab = ( empty( $_GET['tab'] ) ) ? 'manage' : urldecode( $_GET['tab'] );

		?>
		<div class="wrap woocommerce">
			<div id="icon-woocommerce" class="icon32-woocommerce-users icon32"><br /></div>
			<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">

				<?php

				// display tabs
				foreach ( $this->tabs as $tab_id => $tab_title ) {

					$class = ( $tab_id == $current_tab ) ? 'nav-tab nav-tab-active' : 'nav-tab';
					$url   = add_query_arg( 'tab', $tab_id, admin_url( 'admin.php?page=wc_distribution_codes' ) );

					printf( '<a href="%s" class="%s">%s</a>', $url, $class, $tab_title );
				}

				?> </h2> <?php


			// display tab content, default to 'Global Codes' tab
				$this->show_manage_tab();

				?></div> <?php
			}

			private function show_manage_tab() {



		// tab title
				echo '<h2>' . __( 'Global Distribution Codes List', WC_Distribution_Codes::TEXT_DOMAIN ) . '</h2>';

		// show global codes input
				$this->render_settings();

			}


			public function render_settings() {
				global $woocommerce;

				?>
				<div id="update_message"></div>
				<em>Paste your codes here, one code per line:</em><br>
				<textarea id="distribution_codes" style="clear:both; width:100%; height:400px; max-height:80%;margin-top:.5em;" ><?php $this->show_global_codes() ?></textarea>
				<?php

				submit_button( 'Save Codes', 'submit primary', 'save_codes');

			}

			public function show_global_codes(){
				$global_codes = unserialize(get_option('woocommerce_global_distribution_codes'));
				foreach($global_codes as $code){
					echo $code."\n";
				}
			}

			public function save_global_codes() {

				?>
				<script>
				jQuery('#save_codes').click(function(){
					var codes= jQuery('#distribution_codes').val()

					jQuery.ajax({
						type: 'POST',
						dataType: "json",
						url: '/wp-content/plugins/woocommerce-distribution_codes/includes/admin_update_codes.php?save_codes',
						data: {'codes' : codes },
						beforeSend:function(){
						},
						success:function(data){
							if(data == true)
							jQuery('#update_message').prepend('<div id="message" class="updated fade"><p>Codes were successfully saved!</p></div>')
						},
						error:function(){
							alert('Failed!');
						}
					});
				});
				</script>
				<?php

			}

		}

