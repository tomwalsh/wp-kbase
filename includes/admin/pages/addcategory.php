<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

if( isset( $cat ) && sizeof( $cat ) > 0 ) {
	$data = $cat;
} else {
	$data = new stdClass();
}

$select = array();
$select[] = array( 'value' => '0', 'text' => 'No parent' );
foreach( $cats as $item ) {
	$select[] = array( 'value' => $item[ 'id' ], 'text' => $item[ 'name' ] );
}

$this->form( admin_url( 'admin.php?page=wpkbase_categories&wpkbase_task=' . $task . '&noheader=true' ), 'POST' );

if( isset( $data->id ) ) {
	$id = $data->id;
} else {
	$id = 0;
}

$this->input( 'hidden', $id, 'id' );
?>

<div id="category-edit">
	<div id="titlewrap">
		<h3>Name</h3>
		<?php $this->input( 'text', esc_attr( @$data->name ), 'name', 'wpkbase-name-id', 'widefat' ); ?>
	</div>
	<h3>Description</h3>
	<?php wp_editor( esc_attr( @$data->description ), 'wpeditor', array('dfw' => true, 'textarea_name' => 'description', 'editor_height' => 100, 'tabindex' => 2 ) ); ?>
	<br />
	<?php $this->input( 'submit', 'Save Settings', 'submit', 'submit-category', 'button-primary'  ); ?>
	<?php if( $id == 0 ): $this->input( 'submit', 'Save Settings and New', 'submit-new', 'submit-new', 'button-primary'  ); endif; ?>
	<a id="cancel-button" class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpkbase_categories' ) ?>">Cancel</a>
</div>
