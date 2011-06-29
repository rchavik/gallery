<?php

class DdsliderHelper extends AppHelper {

	var $helpers = array(
		'Html',
		);

	function assets($options = array()) {
		$options = Set::merge(array('inline' => false), $options);
		$this->Html->script('/gallery/js/jquery.DDSlider', false, $options);
		$this->Html->css('/gallery/css/DDSlider', false, $options);
	}

}
