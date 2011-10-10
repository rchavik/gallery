<?php
class GalleryShell extends Shell {

	var $tasks = array('GenerateFile');
	var $Gallery = '';

	function __construct($dispatch) {
		App::import('Component', 'Gallery.Gallery');
		$this->Gallery = new GalleryComponent;
		parent::__construct($dispatch);
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
