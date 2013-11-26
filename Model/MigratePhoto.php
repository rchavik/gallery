<?php

App::uses('GalleryAppModel', 'Gallery.Model');

/**
 * Migrate Photo
 *
 * @category Model
 * @package  Gallery
 * @author   Rachman Chavik <oss@xintesa.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class MigratePhoto extends GalleryAppModel {

	public $useTable = 'photos';

	public $alias = 'Photo';

/**
 * Behaviors
 */
	public $actsAs = array(
		'Croogo.Params',
		'Search.Searchable',
	);

/**
 * Model associations: hasAndBelongsToMany
 *
 * @var array
 * @access public
 */
	public $hasAndBelongsToMany = array(
		'Album' => array(
			'className' => 'Gallery.MigrateAlbum',
			'joinTable' => 'albums_photos',
			'foreignKey' => 'photo_id',
			'associationForeignKey' => 'album_id',
			'unique' => 'keepExisting',
			'with' => 'Gallery.AlbumsPhoto',
		)
	);

}
