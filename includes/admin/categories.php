<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

class WPKBASE_categories extends base {
	
	var $methods = array( 'add_category', 'delete_category', 'edit_category' );
	
	function __construct( ) {
		parent::__construct();
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
					$this->show_categories();
					break;
			}			
		} else {
			$this->show_categories();
		}
	}
	
	function show_categories() {
		
		// Default state - display all the categories
		$this->header( 'List Categories' );
		require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'pagination.php';
		
		$items = $this->wpdb->get_var( "SELECT COUNT(*) FROM {$this->prefix}kbasecats" );
		
		$p = new Pagination();
		$p->items( $items );
		$p->limit( 20 );
		$p->target( admin_url( "admin.php?page=wpkbase_categories" ) );
		$p->currentPage( @$_GET[ $p->paging ] );
		$p->calculate();
		$p->parameterName( 'paging' );
		$p->adjacents( 1 );
		if( !isset( $_GET[ 'paging' ] ) ) {
			$p->page = 1;
		} else {
			$p->page = $_GET[ 'paging' ];
		}
		
		$limit = "LIMIT " . ($p->page - 1) * $p->limit . ", " . $p->limit;
		
		$rows = $this->wpdb->get_results( "SELECT * FROM {$this->prefix}kbasecats ORDER BY id ASC {$limit}", ARRAY_A );
		require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'pages' . DS . 'showcategories.php';
		$this->footer( );
	}
	
	function add_category() {
		// Add a category method
		$task = strtolower( $_GET[ 'wpkbase_task' ] );
		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
			// We have been sent data
			// let's check it for validity
			$_POST = array_map( 'stripslashes_deep', $_POST );
			if( trim( $_POST[ 'name' ] ) != '' ) {
				if( (int)$_POST[ 'id' ] > 0 ) {
					// Update an existing title
					$result = $this->wpdb->update(
						$this->wpdb->prefix . 'kbasecats',
						array(
							'parentid' => $_POST[ 'parentid' ],
							'name' => $_POST[ 'name' ],
							'description' => $_POST[ 'description' ]
						),
						array(
							'id' => $_POST[ 'id' ]
						),
						array(
							'%d',
							'%s',
							'%s'
						),
						array(
							'%d'
						)
					);
					wpkbase_setmsg( 'info', 'The category ' . __( $_POST[ 'name' ] ) . ' has been editted.' );
				} else {
					// Insert a new category
					$max_order = $this->wpdb->get_var( "SELECT MAX(`ordering`) FROM {$this->prefix}kbasecats" );
					if( $max_order > 0 ) {
						$new_order = $max_order + 1;
					} else {
						$new_order = 0;
					}
					$result = $this->wpdb->insert( 
						$this->wpdb->prefix . 'kbasecats',
						array( 
							'name' => $_POST[ 'name' ],
							'description' => $_POST[ 'description' ],
							'ordering' => $new_order
						),
						array(
							'%s',
							'%s',
							'%d'
						)
					);
					wpkbase_setmsg( 'info', 'The category ' . __( $_POST[ 'name' ] ) . ' has been created.' );
				}
				if( isset( $_POST[ 'submit-new' ] ) ) {
					wp_redirect( admin_url( 'admin.php?page=wpkbase_categories&wpkbase_task=add_category' ) );
				} else {
					wp_redirect( admin_url( 'admin.php?page=wpkbase_categories' ) );
				}
			}
		} else {
			if( isset( $_GET[ 'id' ] ) && $_GET[ 'id' ] > 0 ) {
				$catid = (int)$_GET[ 'id' ];
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
			$cats = $this->wpdb->get_results( "SELECT * FROM {$this->prefix}kbasecats", ARRAY_A );
			require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'pages' . DS . 'addcategory.php';
			$this->footer();
		}
	}
	
	function delete_category() {
		// This method deletes the category and any underlying articles are disassociated from the category
		$result = $this->wpdb->query(
			$this->wpdb->prepare( "DELETE FROM {$this->wpdb->prefix}kbaselinks WHERE categoryid = %d", (int)$_GET[ 'id' ] )
		);
		
		$result = $this->wpdb->query(
			$this->wpdb->prepare( "DELETE FROM {$this->wpdb->prefix}kbasecats WHERE id = %d", (int)$_GET[ 'id' ] )
		);
		
		wpkbase_setmsg( 'info', 'The category has been deleted.' );
		wp_redirect( admin_url( 'admin.php?page=wpkbase_categories' ) );
		exit();
	}
	
}
global $wpkbase_cat;
$wpkbase_cat = new WPKBASE_categories();