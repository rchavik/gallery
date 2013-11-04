<?php

class AddJoinTable extends CakeMigration {

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

				'albums_photos' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
					'photo_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
					'album_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
					'master' => array('type' => 'boolean', 'null' => false, 'default' => 0),
					'weight' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 11),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'id' => array('column' => array('id'), 'unique' => true),
						'ix_photos_category' => array('column' => array('photo_id', 'album_id'), 'unique' => true),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDb'),
				),
			),
			'drop_field' => array(
				'photos' => array(
					'album_id',
					'weight',
				),
			),
		),

		'down' => array(
			'drop_table' => array(
				'albums_photos',
			),
			'create_field' => array(
				'photos' => array(
					'album_id' => array('type' => 'integer'),
					'weight' => array('type' => 'integer'),
				),
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
