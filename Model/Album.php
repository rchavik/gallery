<?php

App::uses('GalleryAppModel', 'Gallery.Model');

/**
 * Album
 *
 *
 * @category Model
 * @package  Croogo
 * @version  1.3
 * @author   Edinei L. Cipriani <phpedinei@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.edineicipriani.com.br
 */
class Album extends GalleryAppModel {

	public $actsAs = array(
		'Params',
	);

	public $validate = array(
		'slug' => array(
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Slug is already in use.',
			),
			'notEmpty' => array(
				'rule' => 'notEmpty',
			),
		),
	);

/**
 * Model associations: hasAndBelongsToMany
 *
 * @var array
 * @access public
 */
	public $hasAndBelongsToMany = array(
		'Photo' => array(
			'className' => 'Gallery.Photo',
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

	public $findMethods = array(
		'photos' => true,
	);

	protected function _findPhotos($state, $query, $results = array()) {
		if ($state == 'before') {
			$query = Hash::merge($query, array(
				'recursive' => -1,
				'fields' => array('*', 'Photo.*'),
				'order' => 'AlbumsPhoto.weight asc',
				'joins' => array(
					array(
						'alias' => $this->AlbumsPhoto->alias,
						'table' => $this->AlbumsPhoto->useTable,
						'conditions' => 'Album.id = AlbumsPhoto.album_id',
					),
					array(
						'alias' => $this->Photo->alias,
						'table' => $this->Photo->useTable,
						'conditions' => 'Photo.id = AlbumsPhoto.photo_id',
					),
				),
			));
			if (!empty($query['slug'])) {
				$query['conditions']['Album.slug'] = $query['slug'];
			}
			unset($query['slug']);
			return $query;
		} else {
			if (isset($results[0]['Album']['id'])) {
				$album = array('Album' => $results[0]['Album']);
				$photos = Hash::extract($results, '{n}.Photo');
				$album['Photo'] = $photos;
				$results = $album;
			}
			if (isset($results[0]['Album'])) {
				$results = $results[0];
			}
			return $results;
		}
	}

}
