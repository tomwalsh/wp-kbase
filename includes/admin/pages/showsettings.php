<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

$this->form( admin_url( 'admin.php?page=wpkbase_settings&wpkbase_task=save_settings&noheader=true' ), 'POST' );

echo "<table width='100%' cellpadding='0' cellspacing='0' border='0'>\n";

echo "<tr><td>\n<label><strong>Title: </strong></label></td><td>\n";
$this->input( 'text', @$data['title'], 'title', 'wpkbase-title-id', 'wpkbase-title' );
echo "</td></tr>\n";

echo "<tr><td>\n";
$this->input( 'submit', 'Save Settings', 'submit', 'submit-id', 'button-primary'  );
echo "</td><td>&nbsp;</td>\n";

echo "</table>\n";