<?php

App::uses('GalleryAppModel', 'Gallery.Model');

/**
 * MigrateAlbum
 *
 * @category Model
 * @package  Gallery
 * @author   Rachman Chavik <oss@xintesa.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class MigrateAlbum extends GalleryAppModel {

	public $useTable = 'albums';

	public $alias = 'Album';

	public $actsAs = array(
		'Croogo.Params',
	);

/**
 * Model associations: hasAndBelongsToMany
 *
 * @var array
 * @access public
 */
	public $hasAndBelongsToMany = array(
		'Photo' => array(
			'className' => 'Gallery.MigratePhoto',
			'joinTable' => 'albums_photos',
			'foreignKey' => 'album_id',
			'associationForeignKey' => 'photo_id',
			'unique' => 'keepExisting',
			'with' => 'Gallery.AlbumsPhoto',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);

}
