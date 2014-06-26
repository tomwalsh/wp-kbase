<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

$arts_table = new art_list_table();
$arts_table->prepare_items();
$arts_table->display();