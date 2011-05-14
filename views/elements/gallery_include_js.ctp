<?php

$jslibs = Configure::read('Gallery.jslibs');

if (false !== strpos($jslibs, 'nivo-slider')) {
	echo $html->script('/gallery/js/jquery.nivo.slider', false);
	echo $html->css('/gallery/css/nivo-slider', false);
	echo $html->css('/gallery/css/nivo-style', false);
}

if (false !== strpos($jslibs, 'DDSlider')) {
	echo $html->script('/gallery/js/jquery.DDSlider', false);
	echo $html->css('/gallery/css/DDSlider', false);
}


if (false !== strpos($jslibs, 'galleria')) {
	echo $html->script('/gallery/js/galleria', false);
	$code = sprintf('Galleria.loadTheme(\'%s\')',
		$this->Html->url('/gallery/js/themes/classic/galleria.classic.js')
		);
	echo $this->Html->scriptBlock($code, array('inline' => false));
}