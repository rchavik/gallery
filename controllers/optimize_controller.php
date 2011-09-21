<?php
class OptimizeController extends GalleryAppController {
	var $name = 'Optimize';
	var $uses = array();
	var $dir = '';

	function admin_optimize () {
	}

	function getdata() {
		$this->autoRender = false;
		$dirPath = WWW_ROOT . $this->data['Optimize']['path'];
	//	$this->recursedir($dirPath);
		$this->Gallery->recursedir($dirPath);
	}

}
?>
