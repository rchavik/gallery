<?php
class GalleryAppModel extends AppModel {

	public $useDbConfig = 'gallery';

	public $actsAs = array(
		'Containable',
		);

}