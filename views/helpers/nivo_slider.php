<?php

class NivoSliderHelper extends AppHelper {

	var $helpers = array(
		'Html',
		);

	function assets($options = array()) {
		$options = Set::merge(array('inline' => false), $options);
		$this->Html->script('/gallery/js/jquery.nivo.slider', false, $options);
		$this->Html->css('/gallery/css/nivo-slider', false, $options);
		$this->Html->css('/gallery/css/nivo-style', false, $options);
	}

}
