<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

class WPKBASE_settings extends base {

	var $methods = array( 'save_settings' );

	function __construct( ) {
		
		parent::__construct();
		if( isset( $_GET[ 'wpkbase_task' ] ) && in_array( strtolower( $_GET[ 'wpkbase_task' ] ), $this->methods ) ){
			switch( strtolower( $_GET[ 'wpkbase_task' ] ) ) {
				case 'save_settings':
					$this->save_settings();
					break;
						
				default:
					$this->show_settings();
					break;
			}
		} else {
			$this->show_settings();
		}
	}

	function show_settings() {
		// Default state - show the settings
		$this->header( 'Settings' );
		require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'pages' . DS . 'showsettings.php';
		$this->footer( );
	}
	
	function save_settings() {
		// Save the setting
		print_r( $_POST );
		wpkbase_setmsg( 'info', 'Settings have been saved.' );
		//wp_redirect( admin_url( 'admin.php?page=wpkbase_settings' ) );
	}
}

global $wpkbase_settings;
$wpkbase_settings = new WPKBASE_settings();