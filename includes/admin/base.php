<?php

class base {
	var $methods = array( );
	var $wpdb;
	var $prefix;
	var $formcalled = false;
	
	function __construct( ) {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->prefix = $this->wpdb->prefix;
	}
	
	protected function header( $text ) {
		echo "<div class='wrap'>
		<div id='wpkbase-icon' class='icon32' style=\"background: url( '" . WPKBASE_URL . 'images/express-icon-32x32.png' . "' ) no-repeat;\">&nbsp;</div>
		<h2 id='wpkbase-header'>Express Knowledge Base: {$text}</h2>";
	}
	
	protected function footer() {
		if( $this->formcalled ) {
			echo '</form>';
		}
		echo "<div class='clear'>&nbsp;</div>";
		echo "</div>";
	}
	
	protected function form( $action = '', $method = 'GET', $id='wpkbase-form', $multipart=true ) {
		$this->formcalled = true;
		echo "<form action='{$action}' method='{$method}' id='{$id}'";
		if( $multipart == true ) {
			echo ' enctype="multipart/form-data"';
		}
		echo ">";
	}
	
	protected function select( $data, $name='', $id='wpkbase-select', $selected='' ) {
		echo "<select name='{$name}'>\n";
		foreach( $data as $item ) {
			echo "\t<option value='{$item['value']}'>{$item[ 'text' ]}</option>\n";
		}
		echo "</select>\n";
	}
	
	protected function checkbox( $var, $label, $option = 'wpkbase' ) {
		
		$options = $this->get_option( 'wpkbase' );
		if( !isset( $options[ $var ] ) ) {
			$options[ $var ] = false;
		}
		
		if( $options[ $var ] === true ) {
			$options[ $var ] = '1';
		}
		
		$data = "<input type='hidden' name='" . esc_attr( $option ) . "[" . esc_attr( $var ) . "]' value='0' />";
		$data .= "<input type='checkbox' class='checkbox' name='" . esc_attr( $option ) . "[" . esc_attr( $var ) . "]' id='" . esc_attr( $var ) . "' " . checked( $options[ $var ], '1', false ) . ' value="1" />';
		$data_label = "<label class='checkboxlabel' for='" . esc_attr( $var ) . "'>" . $label . "</label>";
		$data_clear = "<div class='clear'>&nbsp</div>";
		return $data . $data_label . $data_clear;
	}
	
	protected function input( $type='text', $value='', $name='', $id='', $class='', $size=50 ) {
		echo "<input type='{$type}' name='{$name}' id='{$id}' class='{$class}' size='{$size}' value='{$value}' />\n";
	}
	
	protected function textarea( $value='', $name='', $id='wpkbase-textarea-id', $class='wpkbase-textarea', $cols=60, $rows=5 ) {
		echo "<textarea name='{$name}' id='{$id}' name='{$class}' rows='{$rows}' cols='{$cols}'>{$value}</textarea>\n";
	}
	
	protected function get_option( $option = 'wpkbase' ) {
		return get_option( $option );
	}
	
}