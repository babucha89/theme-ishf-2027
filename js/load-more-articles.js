// -----------------------------------------------------------------------------
//  Functions: LOAD MORE ARTICLES (AJAX)
// -----------------------------------------------------------------------------
jQuery(function($){
	$('.load-more-articles').click(function(){
		var button = $(this),
		  data = {
  			'action': 'loadmore',
        'query': posts_load_more_articles,
  			'page' : current_page_load_more_articles
		};

		$.ajax({
			// url: '/bs.xl.pt/theme-starter/wp-admin/admin-ajax.php',		// DEV only
			url: '/wp-admin/admin-ajax.php',
			data: data,
			type: 'POST',
			beforeSend: function (xhr) {
				button.find('span').replaceWith('<div data-uk-spinner></div>');
			},
			success: function(data){
				if (data) {
          button.find('.uk-spinner').replaceWith('<span data-uk-icon="chevron-down"></span>');
					$('.loader-grid').append(data);
          $('.loaded-article').hide().fadeIn(1500).removeClass('loaded-article');
					current_page_load_more_articles++;

          // If is last page, remove the button
					if (current_page_load_more_articles == max_page_load_more_articles) button.remove();
				}
        // If no data, remove the button
        else {
					button.remove();
				}
			}
		});
	});
});
