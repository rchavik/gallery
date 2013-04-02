<?php

App::uses('AppHelper', 'View/Helper');

class PhotoswipeHelper extends AppHelper {

	public $helpers = array(
		'Html',
		'Js',
		'Gallery.Gallery',
	);

	public function assets($options = array()) {
		$options = Set::merge(array('inline' => false), $options);

		echo $this->Html->script('/js/klass.min', false, $options);
		echo $this->Html->script('/js/code.photoswipe.jquery-3.0.5.min', false, $options);
		echo $this->Html->css('/css/photoswipe', false, $options);
	}

	public function album($album, $photos) {
		$result = array();
		foreach ($album['Photo'] as $photo) {
			$description = $this->Html->tag('h2', $photo['title']);
			$description .= $this->Html->para('', $photo['description']);
			$description .= $this->Html->link($photo['url'], $photo['url']);
			$result[] = array(
				'url' => '/' . $this->base . $photo['large'],
				'caption' => $album['Album']['description'],
			);
		}
		$galleryId = 'gallery' . Inflector::camelize($album['Album']['slug']);
		$json = json_encode($result);
		return $json;
	}

	public function photo($album, $photo) {
	}

	public function initialize($album) {
		$config = $this->Gallery->getAlbumJsParams($album);
		$galleryId = 'gallery' . Inflector::camelize($album['Album']['slug']);
		$config = Set::merge(array('imageSource' => $galleryId), $config);
	}

}
