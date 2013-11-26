<?php

App::uses('GalleryAppModel', 'Gallery.Model');

class AlbumsPhoto extends GalleryAppModel {

	public $actsAs = array(
		'Croogo.Ordered' => array(
			'field' => 'weight',
			'foreign_key' => 'album_id',
		),
	);

}
