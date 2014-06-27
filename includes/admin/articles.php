<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}



class WPKBASE_articles extends base {
	
	var $methods = array( 'add_article', 'delete_article', 'edit_article' );
	var $wpdb;
	var $prefix;
	
	function __construct( ) {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->prefix = $this->wpdb->prefix;
		
		if( isset( $_GET[ 'wpkbase_task' ] ) && in_array( strtolower( $_GET[ 'wpkbase_task' ] ), $this->methods ) ){
			switch( strtolower( $_GET[ 'wpkbase_task' ] ) ) {
				case 'add_article':
				case 'edit_article':
					$this->add();
					break;
					
				case 'delete_article':
					$this->delete();
					break;
					
				default:
					$this->show();
					break;
			}			
		} else {
			$this->show();
		}
	}
	
	function show() {
		// Default state - display all the articles
		$this->header( 'List Articles ' . '<a href="' . admin_url( 'admin.php?page=wpkbase_articles&wpkbase_task=add_article' ) . '" class="add-new-h2">Add New</a>' );
		require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'pages' . DS . 'showarticles.php';
		$this->footer( );
	}
	
	function add() {
		// Add an article method
		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
			// We have been sent data
			// let's check it for validity
			if( trim( $_POST[ 'title' ] ) != '' ) {
				if( (int)$_POST[ 'id' ] > 0 ) {
					// Update an existing title
					$this->wpdb->query( 
						$this->wpdb->prepare( 
							"UPDATE {$this->prefix}kbasearticles SET 
							title = %s,
							article = %s
							WHERE id = %d",
							stripslashes($_POST[ 'title' ]), stripslashes($_POST[ 'article' ]), (int)$_POST[ 'id' ]
						)
					);
				} else {
					// Insert a new article
					$this->wpdb->query( 
						$this->wpdb->prepare( 
							"INSERT INTO {$this->prefix}kbasearticles SET 
							title = %s,
							article = %s",
							stripslashes($_POST[ 'title' ]), stripslashes($_POST[ 'article' ])
						)
					);
				}
			}
			wp_redirect( admin_url( 'admin.php?page=wpkbase_articles' ) );
		} else {
			if( isset( $_GET[ 'artid' ] ) && $_GET[ 'artid' ] > 0 ) {
				$artid = (int)$_GET[ 'artid' ];
				$art_count = $this->wpdb->get_var( "SELECT COUNT(*) FROM {$this->prefix}kbasearticles WHERE id = {$artid}" );
				if( $art_count > 0 ) {
					// We found the article requested
					$art = $this->wpdb->get_row( "SELECT * FROM {$this->prefix}kbasearticles WHERE id = {$artid}", ARRAY_A );
					$title = "Edit";
				} else {
					// Category requested not found
					$title = "Add";
				}
			} else {
				$title = "Add";
			}
			$this->header( $title . ' Article' );
			require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'pages' . DS . 'addarticle.php';
			$this->footer();
		}
	}
	
	function delete() {
		// This method deletes the category and any underlying articles are disassociated from the category
		$this->wpdb->query(
			$this->wpdb->prepare(
				"DELETE FROM {$this->prefix}kbaselinks 
				WHERE articleid = %d",
				(int)$_GET[ 'artid' ]
			)
		);
		$this->wpdb->query(
			$this->wpdb->prepare(
				"DELETE FROM {$this->prefix}kbasearticles
				WHERE id = %d",
				(int)$_GET[ 'artid' ]
			)
		);
		wp_redirect( admin_url( 'admin.php?page=wpkbase_articles' ) );
		exit();
	}
	
}


class art_list_table extends wpkbase_list_table {

	function __construct() {
		parent::__construct( array(
			'singular' => 'wp_list_text_link',
			'plural' => 'wp_list_text_links',
			'ajax'	=> false
		) );
	}

	function get_columns() {
		return $columns = array(
			'col_art_id' => __('ID'),
			'col_art_title' => __('Title'),
			'col_art_views' => __('Viewed'),
			//'col_art_votes' => __('Votes'),
			'col_art_delete' => __('Delete')
		);
	}

	function get_sortable_columns() {
		return $sortable = array(
			'col_art_id' => array('id', true),
			'col_art_name' => array('title', true)
		);
	}

	function prepare_items() {
		global $wpdb, $_wp_column_headers;
		$screen = get_current_screen();

		$query = "SELECT * FROM {$wpdb->prefix}kbasearticles";

		$orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ASC';
      	$order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : '';
       	if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }

       	$totalitems = $wpdb->query($query);
		$perpage = 20;
        $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';

        if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }

        $totalpages = ceil($totalitems/$perpage);
        //adjust the query to take pagination into account
	    if(!empty($paged) && !empty($perpage)){
	    	$offset=($paged-1)*$perpage;
	        $query.=' LIMIT '.(int)$offset.','.(int)$perpage;
	    }

	    $this->set_pagination_args( array(
	        "total_items" => $totalitems,
	        "total_pages" => $totalpages,
	        "per_page" => $perpage,
	    ) );

	    $columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);

        $this->items = $wpdb->get_results($query);
	}

	function display_rows() {
		$records = $this->items;

		list( $columns, $hidden ) = $this->get_column_info();

 	    if(!empty($records)){
 	    	$i = 1;
 	    	foreach($records as $rec){
 	    		if($i % 2 == 1 ) {
 	    			$class = 'alternate';
 	    		} else {
 	    			$class = '';
 	    		}

			    echo '<tr id="record_'.$rec->id.'" class="' . $class . '">';
	   	        foreach ( $columns as $column_name => $column_display_name ) {
				    $class = "class='$column_name column-$column_name'";
				    $style = "";
				    if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
			    	$attributes = $class . $style;

			    	$editlink = admin_url( 'admin.php?page=wpkbase_articles&wpkbase_task=edit_article&artid=' . (int)$rec->id );
			    	$deletelink = admin_url( 'admin.php?page=wpkbase_articles&wpkbase_task=delete_article&noheader=true&artid=' . (int)$rec->id );

			        switch ( $column_name ) {
			            case "col_art_id":  echo '<td '.$attributes.'>'.stripslashes($rec->id).'</td>';   break;
			            case "col_art_title": echo '<td '.$attributes.'><a href="'. $editlink .'">'.stripslashes($rec->title).'</a></td>'; break;
			            case "col_art_views": echo '<td '.$attributes.'>'.stripslashes($rec->views).'</td>'; break;
			            case "col_art_delete": echo '<td '.$attributes.'><a href="'. $deletelink .'"><img src="' . WPKBASE_URL . DS . 'images/delete.png' . '"/></a></td>'; break;
			        }
			    }
	            echo'</tr>';
	            $i++;
		   }
		}
	}

}

global $wpkbase_art;
$wpkbase_art = new WPKBASE_articles();