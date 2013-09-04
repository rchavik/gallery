<?php
class GalleryMigration extends CakeMigration {

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
		),
		'down' => array(
			'drop_table' => array(
				'albums_photos'
			),
			'drop_field' => array(
				'albums' => array(
					'created'
				),
				'photos' => array(
					'created'
				),
			),
			'create_field' => array(
				'photos' => array(
					'album_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
					'weight' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 11),
				)
			)
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
