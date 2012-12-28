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
			'joinTable' => 'photos_albums',
			'foreignKey' => 'album_id',
			'associationForeignKey' => 'photo_id',
			'unique' => 'keepExisting',
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
