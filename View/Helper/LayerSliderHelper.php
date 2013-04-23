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
				$url = $photo['url'];

				if (empty($url)) {
					$out = $this->Html->image('/' . $this->base . $photo['original'], array(
						'alt' => $photo['title'] . '-' . $alt,
						'class' => 'ls-' . $class,
						'style' => $styles,
					));
				} else {
					$out = $this->Html->link($this->Html->image('/' . $this->base . $photo['original']), $url, array(
						'alt' => $photo['title'] . '-' . $alt,
						'class' => 'ls-' . $class,
						'style' => $styles,
						'escape' => false
					));
				}
				$results .= $out;
			}
		}

		$galleryClass = $album['Album']['params'];

		return $this->Html->div('ls-layer', $results, array(
			'style' => 'slidedirection: top; slidedelay: 7000; durationin: 1500; durationout: 1500; delayout: 500;'));
	}

	public function photo($album, $photo) {
	}

	public function initialize($album) {
		$config = $this->Gallery->getAlbumJsParams($album);
		$galleryId = $album['Album']['params'];
		$js = sprintf('$(\'#%s\').layerSlider({
				skinsPath: "/css/skins/",
				skin: "fullwidth",
				responsive: false,
				responsiveUnder: 1000,
				sublayerContainer: 1000,
				thumbnailNavigation: "disabled",
			});',
			$galleryId
		);

		$js = str_replace('"' . $galleryId . '"', $galleryId, $js);
		$this->Js->buffer($js);
	}

}
