if (typeof Croogo !== 'undefined') {
	if (typeof jQuery !== 'undefined') {
		$(function() {
			if($('#AlbumTitle')){
				$('#AlbumTitle').slug({
					slug: '#AlbumSlug',
					hide: false
				});
			}
		});
	}
}
