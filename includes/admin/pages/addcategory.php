<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

if( isset( $cat ) && sizeof( $cat ) > 0 ) {
	$data = $cat;
} else {
	$data = array();
}

$select = array();
$select[] = array( 'value' => '0', 'name' => 'No parent' );
foreach( $cats as $item ) {
	$select[] = array( 'value' => $item[ 'id' ], 'name' => $item[ 'name' ] );
}

$this->form( admin_url( 'admin.php?page=wpkbase_categories&wpkbase_task=edit_category&noheader=true' ), 'POST' );

if( isset( $data[ 'id' ] ) ) {
	$id = $data[ 'id' ];
} else {
	$id = 0;
}

$this->input( 'hidden', $id, 'id' );

echo "<table width='100%' cellpadding='0' cellspacing='0' border='0'>\n";
echo "<tr><td>\n<label><strong>Parent Category: </strong></label></td><td>\n";
$this->select( $select, 'parentid', 'wpkbase-parentid-id', @$data[ 'parentid' ] );
echo "</td></tr>\n";

echo "<tr><td>\n<label><strong>Name: </strong></label></td><td>\n";
$this->input( 'text', @$data['name'], 'name', 'wpkbase-name-id', 'wpkbase-name' );
echo "</td></tr>\n";

echo "<tr><td>\n<label><strong>Description: </strong></label></td><td>\n";
$this->textarea( @$data['description'], 'description', 'wpkbase-description-id', 'wpkbase-description' );
echo "</td></tr>\n";

echo "<tr><td>\n";
$this->input( 'submit', 'Save Category', 'submit', 'submit-id', 'button-primary'  );
echo "</td><td>&nbsp;</td>\n";

echo "</table>\n";