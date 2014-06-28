<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

class WPKBASE_manage extends base {
	
	var $methods = array( );
	var $wpdb;
	var $prefix;
	var $secret;
	
	function __construct( $ajax = false ) {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->prefix = $this->wpdb->prefix;
		$this->secret = get_site_option( 'kbase_secret' );
		
		if( $ajax === false ) {
			if( isset( $_GET[ 'wpkbase_task' ] ) && in_array( strtolower( $_GET[ 'wpkbase_task' ] ), $this->methods ) ){
				switch( strtolower( $_GET[ 'wpkbase_task' ] ) ) {
					case 'save_settings':
						$this->save();
						break;
						
					default:
						$this->show();
						break;
				}			
			} else {
				$this->show();
			}
		}
	}
	
	function show() {
		// Default state - display all the articles
		$this->header( 'Manage' );
		$cats = $this->wpdb->get_results( "SELECT * FROM {$this->prefix}kbasecats ORDER BY `ordering` ASC", ARRAY_A );
		$arts = $this->wpdb->get_results( "SELECT * FROM {$this->prefix}kbasearticles", ARRAY_A );
		require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'pages' . DS . 'showmanage.php';
		$this->footer( );
	}

	function save() {

	}

}

global $wpkbase_manage;
$wpkbase_manage = new WPKBASE_manage();