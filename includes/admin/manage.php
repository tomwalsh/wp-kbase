<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

class WPKBASE_manage extends base {

	function __construct( ) {
		parent::__construct();
		$this->show_manage();
	}
	
	function show_manage() {
		$this->header( 'Manage' );
		$articles = $this->wpdb->get_results( "SELECT * FROM {$this->prefix}kbasearticles ORDER BY id ASC", ARRAY_A );
		$cats = $this->wpdb->get_results( "SELECT * FROM {$this->prefix}kbasecats ORDER BY ordering ASC", ARRAY_A );
		require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'pages' . DS . 'showmanage.php';
		$this->footer();
	}
}

global $wpkbase_manage;
$wpkbase_manage = new WPKBASE_manage();