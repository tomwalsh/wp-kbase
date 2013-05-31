<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

class WPKBASE_categories extends base {
	
	var $methods = array( 'add_category', 'delete_category', 'edit_category' );
	var $wpdb;
	var $prefix;
	
	function __construct( ) {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->prefix = $this->wpdb->prefix;
		
		if( isset( $_GET[ 'wpkbase_task' ] ) && in_array( strtolower( $_GET[ 'wpkbase_task' ] ), $this->methods ) ){
			switch( strtolower( $_GET[ 'wpkbase_task' ] ) ) {
				case 'add_category':
				case 'edit_category':
					$this->add_category();
					break;
					
				case 'delete_category':
					$this->delete_category();
					break;
					
				default:
					$this->index();
					break;
			}			
		} else {
			$this->index();
		}
	}
	
	function index() {
		
		// Default state - display all the categories
		$this->header( 'List Categories' );
		$rows = $this->wpdb->get_results( 'SELECT * FROM {$this->prefix}kbasecats', ARRAY_A );
		require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'pages' . DS . 'index.php';
		$this->footer( );
	}
	
	function add_category() {
		// Add a category method
		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
			// We have been sent data
			// let's check it for validity
			if( strip( $_POST[ 'title' ] ) != '' ) {
				if( (int)$_POST[ 'id' ] > 0 ) {
					// Update an existing title
				} else {
					// Insert a new title
				}
			}
		} else {
			if( isset( $_GET[ 'catid' ] ) && $_GET[ 'catid' ] > 0 ) {
				$catid = (int)$_GET[ 'catid' ];
				$cat_count = $this->wpdb->get_var( "SELECT COUNT(*) FROM {$this->prefix}kbasecats WHERE id = {$catid}" );
				if( $cat_count > 0 ) {
					// We found the category requested
					$cat = $this->wpdb->get_row( "SELECT * FROM {$this->prefix}kbasecats WHERE id = {$catid}" );
					$title = "Edit";
				} else {
					// Category requested not found
					$title = "Add";
				}
			} else {
				$title = "Add";
			}
			$this->header( $title . ' Category' );
			$cats = $this->wpdb->get_results( 'SELECT * FROM {$this->prefix}kbasecats', ARRAY_A );
			require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'pages' . DS . 'addcategory.php';
			$this->footer();
		}
	}
	
	function delete_category() {
		// This method deletes the category and any underlying articles are disassociated from the category
		wp_redirect( admin_url( 'admin.php?page=wpkbase_categories' ) );
		exit();
	}
	
}
global $wpkbase_cat;
$wpkbase_cat = new WPKBASE_categories();