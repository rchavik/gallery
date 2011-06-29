<?php

class FancyboxHelper extends AppHelper {

	var $helpers = array(
		'Html',
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
		$imgTag = $this->Html->image($urlSmall);
		$out .= $this->Html->tag('a', $imgTag, array('href' => $urlLarge,  'rel' => $albumId, 'class' => 'gallery-thumb'));
		return $out;
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
