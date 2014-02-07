<?php

class Galleries {

	public static $supportedLibs = array(
		'galleria' => 'Galleria',
		'nivo-slider' => 'Nivo Slider',
		'DDSlider' => 'DD Slider',
		'pikachoose' => 'Pikachoose',
		'fancybox' => 'Fancybox',
		'orbit' => 'Orbit',
		'jquery-photofy' => 'jQuery Photofy',
		'jquery-kenburn' => 'jQuery Kenburn',
	);

	public static function activeLibs() {
		$availableLibs = json_decode(Configure::read('Gallery.jslibs'));
		$jslibs = array_fill_keys($availableLibs, true);
		return array_intersect_key(Galleries::$supportedLibs, $jslibs);
	}
}
