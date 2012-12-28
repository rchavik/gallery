<?php

App::uses('AppModel', 'Model');

class GalleryAppModel extends AppModel {

	public $actsAs = array(
		'Containable',
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$useDbConfig = Configure::read('Gallery.useDbConfig');
		if ($useDbConfig) {
			$this->useDbConfig = $useDbConfig;
		}
		parent::__construct($id, $table, $ds);
	}

}