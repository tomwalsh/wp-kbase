<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

$cats_table = new cat_list_table();
$cats_table->prepare_items();
$cats_table->display();