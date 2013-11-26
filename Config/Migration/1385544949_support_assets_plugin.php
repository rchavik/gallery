<?php

class SupportAssetsPlugin extends CakeMigration {

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
				'photos' => array(
					'attachment_id' => array('type' => 'integer'),
					'small_id' => array('type' => 'integer', 'after' => 'small'),
					'large_id' => array('type' => 'integer', 'after' => 'large'),
					'original_id' => array('type' => 'integer', 'after' => 'original'),
				),
			),
		),

		'down' => array(
			'drop_field' => array(
				'photos' => array(
					'attachment_id', 'small_id', 'large_id', 'original_id',
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
