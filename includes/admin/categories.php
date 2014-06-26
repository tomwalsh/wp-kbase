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
					$this->show_categories();
					break;
			}			
		} else {
			$this->show_categories();
		}
	}
	
	function show_categories() {
		// Default state - display all the categories
		$this->header( 'List Categories ' . '<a href="' . admin_url( 'admin.php?page=wpkbase_categories&wpkbase_task=add_category' ) . '" class="add-new-h2">Add New</a>' );
		require WPKBASE_PATH . DS . 'includes' . DS . 'admin' . DS . 'pages' . DS . 'showcategories.php';
		$this->footer( );
	}
	
	function add_category() {
		// Add a category method
		if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
			// We have been sent data
			// let's check it for validity
			if( trim( $_POST[ 'name' ] ) != '' ) {
				if( (int)$_POST[ 'id' ] > 0 ) {
					// Update an existing title
					$this->wpdb->query( 
						$this->wpdb->prepare( 
							"UPDATE {$this->prefix}kbasecats SET 
							parentid = %d,
							name = %s,
							description = %s
							WHERE id = %d",
							(int)$_POST[ 'parentid' ], $_POST[ 'name' ], $_POST[ 'description' ], (int)$_POST[ 'id' ]
						)
					);
				} else {
					// Insert a new title
					$max_order = $this->wpdb->get_Var( "SELECT MAX(ordering) FROM {$this->prefix}kbasecats" );
					$this->wpdb->query( 
						$this->wpdb->prepare( 
							"INSERT INTO {$this->prefix}kbasecats SET 
							parentid = %d,
							name = %s,
							description = %s,
							ordering = %d",
							(int)$_POST[ 'parentid' ], $_POST[ 'name' ], $_POST[ 'description' ], $max_order+1
						)
					);
				}
			}
			wp_redirect( admin_url( 'admin.php?page=wpkbase_categories' ) );
		} else {
			if( isset( $_GET[ 'catid' ] ) && $_GET[ 'catid' ] > 0 ) {
				$catid = (int)$_GET[ 'catid' ];
				$cat_count = $this->wpdb->get_var( "SELECT COUNT(*) FROM {$this->prefix}kbasecats WHERE id = {$catid}" );
				if( $cat_count > 0 ) {
					// We found the category requested
					$cat = $this->wpdb->get_row( "SELECT * FROM {$this->prefix}kbasecats WHERE id = {$catid}", ARRAY_A );
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
		$this->wpdb->query(
			$this->wpdb->prepare(
				"DELETE FROM {$this->prefix}kbaselinks 
				WHERE categoryid = %d",
				(int)$_GET[ 'catid' ]
			)
		);
		$this->wpdb->query(
			$this->wpdb->prepare(
				"DELETE FROM {$this->prefix}kbasecats 
				WHERE id = %d",
				(int)$_GET[ 'catid' ]
			)
		);
		wp_redirect( admin_url( 'admin.php?page=wpkbase_categories' ) );
		exit();
	}
	
}


class cat_list_table extends wpkbase_list_table {

	function __construct() {
		parent::__construct( array(
			'singular' => 'wp_list_text_link',
			'plural' => 'wp_list_text_links',
			'ajax'	=> false
		) );
	}

	function get_columns() {
		return $columns = array(
			'col_cat_id' => __('ID'),
			'col_cat_name' => __('Name'),
			'col_cat_desc' => __('Description'),
			'col_cat_delete' => __('Delete')
		);
	}

	function get_sortable_columns() {
		return $sortable = array(
			'col_cat_id' => array('id', true),
			'col_cat_name' => array('name', true)
		);
	}

	function prepare_items() {
		global $wpdb, $_wp_column_headers;
		$screen = get_current_screen();

		$query = "SELECT * FROM {$wpdb->prefix}kbasecats";

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

			    	$editlink = admin_url( 'admin.php?page=wpkbase_categories&wpkbase_task=edit_category&catid=' . (int)$rec->id );
			    	$deletelink = admin_url( 'admin.php?page=wpkbase_categories&wpkbase_task=delete_category&noheader=true&catid=' . (int)$rec->id );

			        switch ( $column_name ) {
			            case "col_cat_id":  echo '<td '.$attributes.'>'.stripslashes($rec->id).'</td>';   break;
			            case "col_cat_name": echo '<td '.$attributes.'><a href="'. $editlink .'">'.stripslashes($rec->name).'</a></td>'; break;
			            case "col_cat_desc": echo '<td '.$attributes.'>'.stripslashes($rec->description).'</td>'; break;
			            case "col_cat_delete": echo '<td '.$attributes.'><a href="'. $deletelink .'"><img src="' . WPKBASE_URL . DS . 'images/delete.png' . '"/></a></td>'; break;
			        }
			    }
	            echo'</tr>';
	            $i++;
		   }
		}
	}

}

global $wpkbase_cat;
$wpkbase_cat = new WPKBASE_categories();