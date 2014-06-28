var $j = jQuery.noConflict();
$j( document ).ready(function($){
	$( '.kbase-manage-cat-list' ).sortable({
		'axis': 'y',
		'update': function(event,ui) {
			var mydata = $(this).sortable('serialize') + 
			"&nonce=" + $(this).attr('nonce') +
			"&key=" + $(this).attr('key') +
			"&action=wpkbase_ajax" +
			"&task=cat_order_update";
			
			$.ajax({
				data: mydata,
				type: 'POST',
				url: ajaxurl
			});
		}
	});
	$( '.kbase-manage-cat-list' ).disableSelection();
});