jQuery(document).ready(function($) {
	var eqHeight = function(){
		$('.js-eq-h').each(function(){
			var h = 0;
			$(this).find('.js-eq-h-item').each(function(){
				if ( $(this).height() > h ) h = $(this).height();
			});
			$(this).find('.js-eq-h-item').height(h);
		});
	}

	$(window).bind('resize.eq-height', function() {
		eqHeight();
	})
	$(window).trigger('resize.eq-height');
} );

jQuery(window).load(function(){
	$(window).trigger('resize.eq-height');
});

