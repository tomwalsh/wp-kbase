<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

class WPKBASE_ajax extends base {
	
	var $methods = array( 'update_cat_order' );
	
	function __construct( ) {
		parent::__construct();
		
		if( isset( $_POST[ 'wpkbase_task' ] ) && in_array( strtolower( $_POST[ 'wpkbase_task' ] ), $this->methods ) ){
			switch( strtolower( $_POST[ 'wpkbase_task' ] ) ) {
				case 'update_cat_order':
					$this->update_cat_order();
					break;
						
				default:
					exit();
					break;
			}
		} else {
			exit();
		}
	}
	
	function update_cat_order() {
		if( isset( $_POST[ 'cat' ] ) && sizeof( $_POST[ 'cat' ] ) > 0 ) {
			foreach( $_POST[ 'cat' ] as $order=>$id ) {
				$result = $this->wpdb->update(
						$this->wpdb->prefix . 'kbasecats',
						array(
								'ordering' => $order
						),
						array(
								'id' => $id
						),
						array(
								'%d'
						),
						array(
								'%d'
						)
				);
			}
		}
		exit();
	}
}

global $wpkbase_ajax;
$wpkbase_ajax = new WPKBASE_ajax();