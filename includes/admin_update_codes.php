<?php

if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ){


	if(isset($_POST['codes'])){

		$path = $_SERVER['DOCUMENT_ROOT'];

		include_once $path . '/wp-config.php';
		include_once $path . '/wp-load.php';
		include_once $path . '/wp-includes/wp-db.php';
		include_once $path . '/wp-includes/pluggable.php';

		$codes = trim($_POST['codes']);
		$codes = explode("\n",$codes);


		update_option('woocommerce_global_distribution_codes', serialize($codes));

		echo json_encode(true);

	}

}

?>