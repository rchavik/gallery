<?php

class FancyboxHelper extends AppHelper {

	var $helpers = array(
		'Html',
		'Js',
		'Gallery.Gallery',
		);

	function assets($options = array()) {
		$options = Set::merge(array('inline' => false), $options);

		echo $this->Html->script('/gallery/js/fancybox', false, $options);
		echo $this->Html->script('/gallery/js/jquery.mousewheel', false, $options);
		echo $this->Html->css('/gallery/css/fancybox', false, $options);
		echo $this->Html->css('/gallery/css/fancybox-style', false, $options);
	}

	function album($album, $photos) {
		return $this->Html->tag('div', $photos, array(
			'id' => 'gallery-' . $album['Album']['id'],
			));
	}

	function photo($album, $photo) {
		$urlLarge = $this->Html->url('/' . $photo['large']);
		$urlSmall = $this->Html->url('/' . $photo['small']);
		$imgTag = $this->Html->image($urlSmall);
		return $this->Html->tag('a', $imgTag, array(
			'href' => $urlLarge,
			'rel' => 'gallery-' . $album['Album']['id'],
			'class' => 'gallery-thumb'));
	}

	function initialize($album) {
		$config = $this->Gallery->getAlbumJsParams($album);
		$js = sprintf('$(function(){ $(\'a[rel=%s]\').fancybox(%s); })',
			'gallery-' . $album['Album']['id'],
			$config
			);
		$this->Js->buffer($js);
	}

}
