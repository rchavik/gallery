<?php

class GalleriaHelper extends AppHelper {

	var $helpers = array(
		'Html',
		);

	function assets($options = array()) {
		$options = Set::merge(array('inline' => false), $options);
		$this->Html->script('/gallery/js/galleria', false, $options);
		$code = sprintf('Galleria.loadTheme(\'%s\')',
			$this->Html->url('/gallery/js/themes/classic/galleria.classic.js')
			);
		$this->Html->scriptBlock($code, $options);
	}

}
