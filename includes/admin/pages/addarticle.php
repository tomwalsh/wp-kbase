<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

if( isset( $art ) && sizeof( $art ) > 0 ) {
	$data = $art;
} else {
	$data = array();
}

$this->form( admin_url( 'admin.php?page=wpkbase_articles&wpkbase_task=edit_article&noheader=true' ), 'POST' );

if( isset( $data[ 'id' ] ) ) {
	$id = $data[ 'id' ];
} else {
	$id = 0;
}

$this->input( 'hidden', $id, 'id' );

echo "<table width='100%' cellpadding='0' cellspacing='0' border='0'>\n";

echo "<tr><td>\n<label><strong>Title: </strong></label></td><td>\n";
$this->input( 'text', @$data['title'], 'title', 'wpkbase-title-id', 'wpkbase-title' );
echo "</td></tr>\n";

echo "<tr><td>\n<label><strong>Article: </strong></label></td><td>\n";
wp_editor( @$data['article'], 'article' );
echo "</td></tr>\n";

echo "<tr><td>\n";
$this->input( 'submit', 'Save Article', 'submit', 'submit-id', 'button-primary'  );
echo "</td><td>&nbsp;</td>\n";

echo "</table>\n";