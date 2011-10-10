<?php
class OptimizeController extends GalleryAppController {
	var $name = 'Optimize';
	var $uses = array();
	var $dir = '';

	function admin_optimize () {
	}

	function admin_getdata($id = null) {
		if (!$this->RequestHandler->isAjax()) {
			$this->redirect('/');
			return;
		}

		if (!$this->Auth->user()) {
			$this->redirect('/');
			return;
		}

		if (!isset($id)) {
			$id = $this->Auth->user('id');
		}

		$this->autoRender = false;
		$dirPath = WWW_ROOT . $this->data['Optimize']['path'];
	//	$this->recursedir($dirPath);
		$this->Gallery->recursedir($dirPath);
	}

}
?>
