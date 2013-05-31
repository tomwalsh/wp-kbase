<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

echo '<a class="button-secondary" href="' . admin_url( 'admin.php?page=wpkbase_categories&wpkbase_task=add_category') . '">' . __( 'Add New Category', 'wpkbase' ) . '</a>';

if( sizeof( $rows ) == 0 ) {
	// No categories are set
} else {
	// loop through the categories
	foreach( $rows as $row ) {
		
	}
}