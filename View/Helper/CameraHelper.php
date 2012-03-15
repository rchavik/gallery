<?php

class CameraHelper extends AppHelper {

	var $helpers = array(
		'Html',
		'Js',
		'Gallery.Gallery',
		);

	function assets($options = array()) {
		$options = Set::merge(array('inline' => false), $options);
		$this->Html->script('/gallery/js/camera', false, $options);
		$this->Html->css('/gallery/css/camera', false, $options);
	}

	function album($album, $photos) {
		$galleryId = 'gallery' . Inflector::camelize($album['Album']['slug']);
		$result = $this->Html->div('camera_wrap camera_azure_skin', $photos, array( 'id' => $galleryId));
		return $result;
	}
	
	function photo($album, $photo) {
		$options = array(
			'data-src' => '/' . $this->base . $photo['original'],
			'data-thumb' => '/' . $this->base . $photo['small'],
			);
		//debug($photo);
		$imgTag = $this->Html->image('/' . $this->base . $photo['original'], array(
				'alt' => 'img',
				'style' => 'width:950px; background:transparent;'
				));
		$imgDes = $this->Html->div('camera_caption fadeFromLeft', $this->base . $photo['description']);
		
		$results = $this->Html->div('camera_images', $imgDes , $options);
		return $results;
	}

	function initialize($album) {
		$config = $this->Gallery->getAlbumJsParams($album);
		$galleryId = 'gallery' . Inflector::camelize($album['Album']['slug']);
		$js = sprintf('$(\'#%s\').camera(%s);',
			$galleryId,
			$config
			);

		$js = str_replace('"' . $galleryId . '"', $galleryId, $js);
		$this->Js->buffer($js);
	}

}
