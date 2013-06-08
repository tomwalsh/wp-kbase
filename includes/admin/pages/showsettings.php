<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

?>


<?php $this->form( admin_url( 'admin.php?page=wpkbase_settings&wpkbase_task=save_settings&noheader=true' ), 'POST' ); ?>
<div class="subsection">
	<h3>General Settings</h3>
</div>
<div class="subsection">
	<h3>Category Settings</h3>
	<div class="setting-item">
		<?php echo $this->checkbox( 'display_empty_cats', 'Display empty categories to users' );?>
	</div>
	<div class="setting-item">
		<?php echo $this->checkbox( 'display_cat_desc', 'Display category description' );?>
	</div>
</div>
<div class="subsection">
	<h3>Article Settings</h3>
</div>

<?php $this->input( 'submit', 'Save Settings', 'submit', 'submit-settings', 'button-primary'  ); ?>