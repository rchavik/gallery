<?php

App::uses('AppHelper', 'View/Helper');

class GalleriaHelper extends AppHelper {

	public $helpers = array(
		'Html',
		'Js',
		'Gallery.Gallery',
	);

	public function assets($options = array()) {
		$options = Set::merge(array('inline' => false), $options);
		$this->Html->script('/gallery/js/galleria', false, $options);
		$code = sprintf('Galleria.loadTheme(\'%s\')',
			$this->Html->url('/gallery/js/themes/classic/galleria.classic.js')
		);
		$this->Html->scriptBlock($code, $options);
	}

	public function album($album, $photos) {
		return $this->Html->tag('div', $photos, array(
			'id' => 'gallery-' . $album['Album']['id'],
		));
	}

	public function photo($album, $photo) {
		$urlLarge = $this->Html->url('/' . $photo['large']);
		$urlSmall = $this->Html->url('/' . $photo['small']);
		$imgTag = $this->Html->image($urlSmall);
		return $this->Html->tag('a', $imgTag, array('href' => $urlLarge));
	}

	public function initialize($album) {
		$config = $this->Gallery->getAlbumJsParams($album);
		$js = sprintf('$(\'#%s\').galleria(%s);',
			'gallery-' . $album['Album']['id'],
			$config
		);
		$this->Js->buffer($js);
	}

}
