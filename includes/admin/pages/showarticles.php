<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

?>
<div class="buttons">
<a class="button-secondary" href="<?php echo admin_url( 'admin.php?page=wpkbase_articles&wpkbase_task=add_article'); ?>"><?php echo __( 'Add New Article', 'wpkbase' ); ?></a>
<div class="tablenav">
    <div class='tablenav-pages'>
        <?php echo $p->show();  // Echo out the list of paging. ?>
    </div>
</div>
</div>

<div class="clear">&nbsp;</div>

<table class="widefat" id="articles-table">
	<thead>
		<tr>
			<th>Article ID</th>
			<th>Article Title</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>Article ID</th>
			<th>Article Title</th>
			<th>Actions</th>
		</tr>
	</tfoot>
	<tbody>
	<?php if( sizeof( $rows ) > 0 ):?>
		<?php foreach( $rows as $row ): ?>
		<tr>
			<td><?php echo $row[ 'id' ]; ?></td>
			<td><?php echo esc_attr( $row[ 'title' ] );?></td>
			<td><a href="<?php echo admin_url( "admin.php?page=wpkbase_articles&wpkbase_task=edit_article&id={$row[ 'id' ]}" ); ?>" class="editbutton">Edit</a> | 
			<a href="<?php echo admin_url( "admin.php?page=wpkbase_articles&wpkbase_task=delete_article&id={$row[ 'id' ]}&noheader=true" ); ?>" class="deletebutton">Delete</a> </td>
		</tr>
		<?php endforeach;?>
	<?php else: ?>
		<tr>
			<td colspan="3" class="nonefound">There are no articles currently.</td>
		</tr>
	<?php endif; ?>
	</tbody>
</table>