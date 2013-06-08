<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

class WPKBASE_articles extends base {
	
	var $methods = array( 'add_article', 'delete_article', 'edit_article' );
	
	function __construct( ) {
		parent::__construct();
		if( isset( $_GET[ 'wpkbase_task' ] ) && in_array( strtolower( $_GET[ 'wpkbase_task' ] ), $this->methods ) ){
			switch( strtolower( $_GET[ 'wpkbase_task' ] ) ) {
				case 'add_article':
				case 'edit_article':
					$this->add_article();
					break;
					
				case 'delete_article':
					$this->delete_article();
					break;
					
				default:
					$this->show_articles();
					break;
			}			
		} else {
			$this->show_articles();
		}
	}
	
	function show_articles() {
		
		// Default state - display all the articles
		$this->header( 'List Articles' );
		require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'pagination.php';
		
		$items = $this->wpdb->get_var( "SELECT COUNT(*) FROM {$this->prefix}kbasearticles" );
		
		$p = new Pagination();
		$p->items( $items );
		$p->limit( 20 );
		$p->target( admin_url( "admin.php?page=wpkbase_articles" ) );
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
		
		$rows = $this->wpdb->get_results( "SELECT * FROM {$this->prefix}kbasearticles ORDER BY id ASC {$limit}", ARRAY_A );
		require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'pages' . DS . 'showarticles.php';
		$this->footer( );
	}
	
	function add_article() {
		// Add a article method
		$task = strtolower( $_GET[ 'wpkbase_task' ] );
		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
			// We have been sent data
			// let's check it for validity
			$_POST = array_map( 'stripslashes_deep', $_POST );
			if( trim( $_POST[ 'title' ] ) != '' && trim( $_POST[ 'article' ] != '' ) ) {
				if( (int)$_POST[ 'id' ] > 0 ) {
					// Update an existing title
					$result = $this->wpdb->update(
						$this->wpdb->prefix . 'kbasearticles',
						array(
							'title' => $_POST[ 'title' ],
							'article' => $_POST[ 'article' ]
						),
						array(
							'id' => $_POST[ 'id' ]
						),
						array(
							'%s',
							'%s'
						),
						array(
							'%d'
						)
					);
					wpkbase_setmsg( 'info', 'The article ' . __( $_POST[ 'title' ] ) . ' has been editted.' );
				} else {
					// Insert a new article
					$result = $this->wpdb->insert( 
						$this->wpdb->prefix . 'kbasearticles',
						array( 
							'title' => $_POST[ 'title' ],
							'article' => $_POST[ 'article' ]
						),
						array(
							'%s',
							'%s'
						)
					);
					wpkbase_setmsg( 'info', 'The article ' . __( $_POST[ 'title' ] ) . ' has been created.' );
				}
				if( isset( $_POST[ 'submit-new' ] ) ) {
					wp_redirect( admin_url( 'admin.php?page=wpkbase_articles&wpkbase_task=add_article' ) );
				} else {
					wp_redirect( admin_url( 'admin.php?page=wpkbase_articles' ) );
				}
			}
		} else {
			if( isset( $_GET[ 'id' ] ) && $_GET[ 'id' ] > 0 ) {
				$articleid = (int)$_GET[ 'id' ];
				$cat_count = $this->wpdb->get_var( "SELECT COUNT(*) FROM {$this->prefix}kbasearticles WHERE id = {$articleid}" );
				if( $cat_count > 0 ) {
					// We found the article requested
					$cat = $this->wpdb->get_row( "SELECT * FROM {$this->prefix}kbasearticles WHERE id = {$articleid}" );
					$title = "Edit";
				} else {
					// Article requested not found
					$title = "Add";
				}
			} else {
				$title = "Add";
			}
			$this->header( $title . ' Article' );
			$cats = $this->wpdb->get_results( "SELECT * FROM {$this->prefix}kbasearticles", ARRAY_A );
			require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'pages' . DS . 'addarticle.php';
			$this->footer();
		}
	}
	
	function delete_article() {
		// This method deletes the category and any underlying articles are disassociated from the category
		$result = $this->wpdb->query(
			$this->wpdb->prepare( "DELETE FROM {$this->wpdb->prefix}kbaselinks WHERE articleid = %d", (int)$_GET[ 'id' ] )
		);
		
		$result = $this->wpdb->query(
			$this->wpdb->prepare( "DELETE FROM {$this->wpdb->prefix}kbasearticles WHERE id = %d", (int)$_GET[ 'id' ] )
		);
		
		wpkbase_setmsg( 'info', 'The article has been deleted.' );
		wp_redirect( admin_url( 'admin.php?page=wpkbase_articles' ) );
		exit();
	}
	
}
global $wpkbase_articles;
$wpkbase_articles = new WPKBASE_articles();