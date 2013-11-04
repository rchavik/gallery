<?php

class FirstMigrationGalery extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'albums' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
					'position' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 11),
					'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 15),
					'status' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'max_width' => array('type' => 'integer', 'null' => true),
					'max_height' => array('type' => 'integer', 'null' => true),
					'max_height_thumbnail' => array('type' => 'integer', 'null' => true),
					'max_width_thumbnail' => array('type' => 'integer', 'null' => true),
					'quality' => array('type' => 'integer', 'null' => true),
					'params' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'id' => array('column' => array('id'), 'unique' => true),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDb'),
				),
				'photos' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
					'album_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
					'title' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'url' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 255),
					'small' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 1024),
					'large' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 1024),
					'original' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 1024),
					'status' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'weight' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 11),
					'params' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'id' => array('column' => array('id'), 'unique' => true),
						'fk_photos_albums' => array('column' => array('album_id'), 'unqie' => true),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDb'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'albums', 'photos',
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		if ($direction == 'down' && Configure::read('debug') == 0) {
			return false;
		}
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
