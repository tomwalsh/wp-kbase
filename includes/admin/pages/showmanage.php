<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

$key = time();

echo "<div class='kbase-manage-page'>\n";
echo "	<div class='kbase-manage-art-box'>\n";
echo "	<h2 class='kbase-title'>Articles</h2>\n";
echo "		<div class='kbase-manage-art-list'>\n";
		
foreach( $arts as $item ) {
	echo "			<div class='kbase-manage-art-item'>{$item['title']}</div>\n";
}

echo "		</div>\n";
echo "	</div>\n";
echo "	<div class='kbase-manage-cat-box'>\n";
echo "	<h2 class='kbase-title'>Categories</h2>\n";
echo "		<div class='kbase-manage-cat-list' nonce='" . wp_create_nonce( $this->secret . $key ) . "' key='" . $key . "'>\n";

foreach( $cats as $item ) {
	echo "			<div id='item-{$item['id']}' class='kbase-manage-cat-item'>{$item['name']}</div>\n";
}

echo "		</div>\n";
echo "	</div>\n";
echo "</div>\n";