<?php

App::uses('GalleryAppModel', 'Gallery.Model');

class AlbumsPhoto extends GalleryAppModel {

	public $actsAs = array(
		'Ordered' => array(
			'field' => 'weight',
			'foreign_key' => 'album_id',
		),
	);

}
