<?php

CroogoNav::add('extensions.children.gallery', array(
	'title' => 'Gallery',
	'url' => array(
		'plugin' => 'gallery',
		'admin' => true,
		'controller' => 'albums',
		'action' => 'index',
	),
	'children' => array(
		'albums' => array(
			'title' => 'Albums',
			'url' => array(
				'plugin' => 'gallery',
				'admin' => true,
				'controller' => 'albums',
				'action' => 'index',
			),
		),

		'photos' => array(
			'title' => __d('gallery', 'Photos'),
			'url' => array(
				'plugin' => 'gallery',
				'admin' => true,
				'controller' => 'photos',
				'action' => 'index',
			),
		),

		'settings' => array(
			'title' => __d('gallery', 'Settings'),
			'url' => array(
				'plugin' => 'settings',
				'admin' => true,
				'controller' => 'settings',
				'action' => 'prefix',
				'Gallery',
			)
		),

	),

));
