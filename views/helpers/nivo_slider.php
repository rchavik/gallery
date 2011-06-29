<?php

class NivoSliderHelper extends AppHelper {

	var $helpers = array(
		'Html',
		'Js',
		'Gallery.Gallery',
		);

	function assets($options = array()) {
		$options = Set::merge(array('inline' => false), $options);
		$this->Html->script('/gallery/js/jquery.nivo.slider', false, $options);
		$this->Html->css('/gallery/css/nivo-slider', false, $options);
		$this->Html->css('/gallery/css/nivo-style', false, $options);
	}

	function album($album, $photos) {
		return $this->Html->tag('div', $photos, array(
			'id' => 'gallery-' . $album['Album']['id'],
			));
	}

	function photo($album, $photo) {
		$title = empty($photo['title']) ? false : $photo['title'];
		$urlLarge = $this->Html->url('/img/photos/' . $photo['large']);
		$urlSmall = $this->Html->url('/img/photos/' . $photo['small']);
		$options = Set::merge(array('rel' => $urlSmall), $options);
		if ($title) {
			$options = Set::merge(array('title' => $title), $options);
		}
		return $this->Html->image($urlLarge, $options);

	}

	function initialize($album) {
		$config = $this->Gallery->getAlbumJsParams($album);
		$js = sprintf('$(function(){ $(\'#%s\').nivoSlider(%s); })',
			'gallery-' . $album['Album']['id'],
			$config
			);
		$this->Js->buffer($js);
	}

}
