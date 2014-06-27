<?php

class base {
	
	var $formcalled = false;
	
	protected function header( $text ) {
		echo "<div class='wrap'>
		<h2 id='kbase-header'>
			<img style='vertical-align: middle;' src='" . WPKBASE_URL . 'images/express-icon-32x32.png' . "'/>
			&nbsp;Knowledge Base: {$text}
		</h2>\n";
	}
	
	protected function footer() {
		if( $this->formcalled ) {
			echo '</form>';
		}
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
			if( $selected == $item['value'] ) {
				echo 'foo';
				$selectedtext = ' selected';
			} else {
				$selectedtext = '';
			}
			echo "\t<option value='{$item['value']}'" . $selectedtext . ">{$item['name']}</option>\n";
		}
		echo "</select>\n";
	}
	
	protected function input( $type='text', $value='', $name='', $id='', $class='', $size=50 ) {
		echo "<input type='{$type}' name='{$name}' id='{$id}' class='{$class}' size='{$size}' value='{$value}' />\n";
	}
	
	protected function textarea( $value='', $name='', $id='wpkbase-textarea-id', $class='wpkbase-textarea', $cols=60, $rows=5 ) {
		echo "<textarea name='{$name}' id='{$id}' name='{$class}' rows='{$rows}' cols='{$cols}'>{$value}</textarea>\n";
	}
	
}