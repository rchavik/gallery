<?php

App::uses('AppHelper', 'View/Helper');

class JqueryPhotofyHelper extends AppHelper {

	public $helpers = array(
		'Html',
		'Js',
		'Gallery.Gallery',
	);

	public function assets($options = array()) {
		$options = Set::merge(array('inline' => false), $options);

		echo $this->Html->script('/gallery/js/jquery-photofy', false, $options);
		echo $this->Html->css('/gallery/css/photofy', false, $options);
	}

	public function album($album, $photos) {
		$result = array();
		foreach ($album['Photo'] as $photo) {
			$description = $this->Html->tag('h2', $photo['title']);
			$description .= $this->Html->para('', $photo['description']);
			$description .= $this->Html->link($photo['url'], $photo['url']);
			$result[] = array(
				'ImageUrl' => '/' . $this->base . $photo['large'],
				'LinkUrl' => '/' . $this->base . $photo['large'],
				'HTML' => $description,
			);
		}
		$galleryId = 'gallery' . Inflector::camelize($album['Album']['slug']);
		$json = json_encode($result);
		$script =<<<EOF
var $galleryId = $json;
EOF;
		$this->Js->buffer($script);
		echo $this->Html->div('photofy', '', array('id' => $galleryId));
	}

	public function photo($album, $photo) {
		/*
		$urlLarge = $this->Html->url('/' . $photo['large']);
		$urlSmall = $this->Html->url('/' . $photo['small']);
		$imgTag = $this->Html->image($urlSmall);
		return $this->Html->tag('a', $imgTag, array(
			'href' => $urlLarge,
			'rel' => 'gallery-' . $album['Album']['id'],
			'class' => 'gallery-thumb'));
		*/
	}

	public function initialize($album) {
		$config = $this->Gallery->getAlbumJsParams($album);
		$galleryId = 'gallery' . Inflector::camelize($album['Album']['slug']);
		$config = Set::merge(array('imageSource' => $galleryId), $config);
		$js = sprintf('$(\'#%s\').photofy(%s);',
			$galleryId,
			json_encode($config)
			);

		$js = str_replace('"' . $galleryId . '"', $galleryId, $js);
		$this->Js->buffer($js);
	}

}
