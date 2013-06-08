<?php

if( !defined( 'WPKBASE_VERSION' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die();
}

?>
<div class="column-left">
	<div class="sidebar-name">
		<h3>Articles</h3>
	</div>
	<div class="article-holder ui-droppable">
		<p class="description">
			Drag articles from here to the categories on the right. An article can appear in more than one category.
		</p>
<?php if( sizeof( $articles ) > 0 ):?>
	<?php foreach( $articles as $article ): ?>
		<div id="article_<?php echo $article[ 'id' ]; ?>" class="article-item ui-draggable">
			<h4><?php echo $article[ 'title' ]; ?></h4>
		</div>
	<?php endforeach;?>
<?php else:?>
		There are no articles currently.
<?php endif;?>
	</div>
</div>
<div class="column-right">
	<div class="sidebar-name">
		<h3>Categories</h3>
	</div>
	<div class="category-holder ui-sortable">
		<p class="description">
			Expand a category and then drag and drop an article to assign it that category. Drag a category to change its display order.
		</p>
<?php if( sizeof( $cats ) > 0 ):?>
	<ul class="category-sort">
	<?php foreach( $cats as $cat ): ?>
		<li id="cat_<?php echo $cat[ 'id' ]; ?>" class="category-item">
			<div class="category-title">
				<div class="category-title-action">
					<a href="#" class="category-action"></a>
				</div>
				<h4><?php echo $cat[ 'name' ]; ?></h4>
				<div class="clear">&nbsp;</div>
			</div>
			<div class="category-inner">
				
			</div>
		</li>
	<?php endforeach;?>
	</ul>
<?php else:?>
		There are no categories currently.
<?php endif;?>
	</div>
</div>
<div class="clear">&nbsp;</div>

<script type="text/javascript">
	var $j = jQuery.noConflict();
	$j( document ).ready( function() {
		$j( '.category-action' ).click( function() {
			var parent = $j( this ).parents( '.category-item' ).find( '.category-inner' );
			$j( parent ).slideToggle( 'slow' );
		});

		$j( '.category-sort' ).sortable({
			update: function() {
				var serial = $j( '.category-sort' ).sortable( 'serialize' );
				var mydata = serial + '&action=wpkbase_ajax&wpkbase_task=update_cat_order';
				$j.ajax({
					url: ajaxurl,
					type: 'post',
					data: mydata,
					error: function() {
						alert( 'There was a problem with the ajax update.');
					}
				});
			}
		});

		$j( '.category-inner' ).sortable({
			placeholder: 'article-placeholder'

		});

		$j( '.article-item' ).draggable({
			connectToSortable: 'div.category-inner',
			containment: 'document',
			distance: 2,
			helper: 'clone',
			revert: 'invalid',
			snap: true,
			snapMode: 'inner'
		});
	});
</script>