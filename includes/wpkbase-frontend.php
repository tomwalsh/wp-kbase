<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

class WPKBASE_Frontend {
	
	function __construct() {
		add_shortcode( 'wpkbase', array( $this, 'display_frontend' ) );
	}
	
	function display_frontend() {
		
	}
	
}



global $wpkbase_frontend;
$wpkbase_frontend = new WPKBASE_Frontend();