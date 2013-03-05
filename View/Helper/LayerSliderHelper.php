<?php

App::uses('AppHelper', 'View/Helper');

class LayerSliderHelper extends AppHelper {

	public $helpers = array(
		'Html',
		'Js',
		'Gallery.Gallery',
	);

	public function assets($options = array()) {
	}

	public function album($album, $photos) {
		$explode = '';
		$results = '';
		foreach ($album['Photo'] as $photo) {
			// params: alt,class,style
			if (!empty($photo['params'])) {
				$explode = explode(',', $photo['params']);

				$styles = isset($explode[2]) ? $explode[2]: '';

				if (count($explode) > 1) {
					list($alt, $class) = $explode;
				}

				$results .= $this->Html->image('/' . $this->base . $photo['original'], array(
					'alt' => $photo['title'] . '-' . $alt,
					'class' => 'ls-' . $class,
					'style' => $styles,
				));
			}
		}

		$galleryClass = $album['Album']['params'];

		return $this->Html->div('ls-layer', $results);
	}

	public function photo($album, $photo) {
	}

	public function initialize($album) {
		$config = $this->Gallery->getAlbumJsParams($album);
		$galleryId = $album['Album']['params'];
		$js = sprintf('$(\'#%s\').layerSlider({
				skinsPath: "/css/skins/",
				skin: "fullwidth",
				responsive: true,
				thumbnailNavigation: "hover",
			});',
			$galleryId
		);

		$js = str_replace('"' . $galleryId . '"', $galleryId, $js);
		$this->Js->buffer($js);
	}

}
