<?php

class OrbitHelper extends AppHelper {

	var $helpers = array(
		'Html',
		'Js',
		'Gallery.Gallery',
		);

	function assets($options = array()) {
	}

	function album($album, $photos) {
		return $this->Html->tag('div', $photos, array(
			'id' => 'gallery-' . $album['Album']['id'],
			));
	}

	function photo($album, $photo) {
		$urlLarge = $this->Html->url('/' . $photo['large']);
		$urlSmall = $this->Html->url('/' . $photo['small']);
		return $this->Html->image($urlLarge);
	}

	function initialize($album) {
		$config = $this->Gallery->getAlbumJsParams($album);
		$js = sprintf('$(\'#%s\').orbit(%s);',
			'gallery-' . $album['Album']['id'],
			$config
			);
		$this->Js->buffer($js);
	}

}
