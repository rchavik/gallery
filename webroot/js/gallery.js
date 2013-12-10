if (typeof Croogo !== 'undefined') {
	if (typeof jQuery !== 'undefined') {
		$(function() {
			var $albumTitle = $('#AlbumTitle');
			if ($albumTitle.length > 0) {
				$('#AlbumTitle').slug({
					slug: '#AlbumSlug',
					hide: false
				});
			}
		});
	}
}
