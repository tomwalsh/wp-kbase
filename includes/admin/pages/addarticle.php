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

$this->form( admin_url( 'admin.php?page=wpkbase_articles&wpkbase_task=' . $task . '&noheader=true' ), 'POST' );

if( isset( $data->id ) ) {
	$id = $data->id;
} else {
	$id = 0;
}

$this->input( 'hidden', $id, 'id' );

?>
<div id="article-edit">
	<div id="titlewrap">
		<h3>Title</h3>
		<?php $this->input( 'text', esc_attr( @$data->title ), 'title', 'wpkbase-title-id', 'widefat' ); ?>
	</div>
	<h3>Article</h3>
	<?php wp_editor( @$data->article, 'wpeditor', array('dfw' => true, 'textarea_name' => 'article', 'editor_height' => 360) ); ?>
	<br />
	<?php $this->input( 'submit', 'Save Settings', 'submit', 'submit-article', 'button-primary'  ); ?>
	<?php if( $id == 0 ): $this->input( 'submit', 'Save Settings and New', 'submit-new', 'submit-new', 'button-primary'  ); endif; ?>
	<a id="cancel-button" class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpkbase_articles' ) ?>">Cancel</a>
</div>
