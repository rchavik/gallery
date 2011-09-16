<?php

class PikachooseHelper extends AppHelper {

	var $helpers = array(
		'Html',
		'Js',
		'Gallery.Gallery',
		);

	function assets($options = array()) {
		$options = Set::merge(array('inline' => false, 'once' => true), $options);
		echo $this->Html->script('/gallery/js/jquery.pikachoose.full', $options);
		echo $this->Html->css('/gallery/css/pikachoose', false, $options);
	}

	function album($album, $photos) {
		$ulTag = $this->Html->tag('ul', $photos, array(
			'id' => 'gallery-' . $album['Album']['id'],
			));
		return $this->Html->tag('div', $ulTag, array('class' => 'pikachoose'));
	}

	function photo($album, $photo) {
		$urlLarge = $this->Html->url('/' . $photo['large']);
		$urlSmall = $this->Html->url('/' . $photo['small']);
		$title = empty($photo['title']) ? false : $photo['title'];
		$options = array('rel' => $urlSmall);
		if ($title) {
			$options = Set::merge(array('title' => $title), $options);
		}
		return $this->Html->tag('li', $this->Html->image($urlLarge, $options));
	}

	function initialize($album) {
		$config = $this->Gallery->getAlbumJsParams($album);
		$js = sprintf('$(\'#%s\').PikaChoose(%s);',
			'gallery-' . $album['Album']['id'],
			$config
			);
		$this->Js->buffer($js);
	}
}
