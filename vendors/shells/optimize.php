<?php
class OptimizeShell extends Shell {

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
	cake optimize generate <id>
EOF;
		$this->out($helptext);
	}

	function generate() {
		if (empty($this->args)) {
			$this->help();
			return false;
		}

		$file = WWW_ROOT . 'galleries' . DS . $this->args[0];
		$this->GenerateFile->execute();
		$processed = $this->Gallery->recursedir($file);
	}
}
?>
