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
			'create_field' => array(
				'albums' => array(
					'created' => array('type' => 'datetime', 'null' => true),
				),
				'photos' => array(
					'created' => array('type' => 'datetime', 'null' => true),
				)
			),
		),
		'down' => array(
			'drop_field' => array(
				'albums' => array(
					'created'
				),
				'photos' => array(
					'created'
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
