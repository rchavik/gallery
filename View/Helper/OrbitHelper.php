<?php

App::uses('AppHelper', 'View/Helper');

class OrbitHelper extends AppHelper {

	public $helpers = array(
		'Html',
		'Js',
		'Gallery.Gallery',
	);

	public function assets($options = array()) {
	}

	public function album($album, $photos) {
		return $this->Html->tag('div', $photos, array(
			'id' => 'gallery-' . $album['Album']['id'],
		));
	}

	public function photo($album, $photo) {
		$urlLarge = $this->Html->url('/' . $photo['large']);
		$urlSmall = $this->Html->url('/' . $photo['small']);
		return $this->Html->image($urlLarge);
	}

	public function initialize($album) {
		$config = $this->Gallery->getAlbumJsParams($album);
		$js = sprintf('$(\'#%s\').orbit(%s);',
			'gallery-' . $album['Album']['id'],
			$config
		);
		$this->Js->buffer($js);
	}

}
