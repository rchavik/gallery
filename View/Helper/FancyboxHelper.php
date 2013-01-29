<?php

App::uses('AppHelper', 'View/Helper');

class FancyboxHelper extends AppHelper {

	public $helpers = array(
		'Html',
		'Js',
		'Gallery.Gallery',
	);

	public function assets($options = array()) {
		$options = Set::merge(array('inline' => false), $options);

		echo $this->Html->script('/gallery/js/fancybox', $options);
		echo $this->Html->script('/gallery/js/jquery.mousewheel', $options);
		echo $this->Html->css('/gallery/css/fancybox', false, $options);
		echo $this->Html->css('/gallery/css/fancybox-style', false, $options);
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
		return $this->Html->tag('a', $imgTag, array(
			'href' => $urlLarge,
			'rel' => 'gallery-' . $album['Album']['id'],
			'class' => 'gallery-thumb',
		));
	}

	public function initialize($album) {
		$config = $this->Gallery->getAlbumJsParams($album);
		$js = sprintf('$(\'a[rel=%s]\').fancybox(%s);',
			'gallery-' . $album['Album']['id'],
			$config
		);
		$this->Js->buffer($js);
	}

}
