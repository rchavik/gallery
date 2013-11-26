<?php

App::uses('Controller', 'Controller');
App::uses('ComponentCollection', 'Controller');
App::uses('GalleryComponent', 'Gallery.Controller/Component');

class GalleryShell extends Shell {

	var $tasks = array('GenerateFile');
	var $Gallery = '';

	public function __construct($stdout = null, $stderr = null, $stdin = null) {
		$collection = new ComponentCollection();
		$this->Gallery = new GalleryComponent($collection);
		parent::__construct($stdout, $stderr, $stdin);
	}

	function help() {
		$helptext =<<<EOF
Usage:
	cake gallery optimize <id>
EOF;
		$this->out($helptext);
	}

	function optimize() {
		if (empty($this->args)) {
			$this->help();
			return false;
		}

		$file = WWW_ROOT . 'galleries' . DS . $this->args[0];
		if(!file_exists($file)) {
			$this->out('Gallery not optimize, Id not exist');
			return;
		}
		$this->Gallery->recursedir($file);
		$this->out('OK');
	}
}
?>
