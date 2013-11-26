<?php

if (file_exists(CakePlugin::path('Gallery') . 'Config/gallery.php')) {
	Configure::load('Gallery.gallery');
}

Croogo::hookRoutes('Gallery');

Croogo::hookComponent('*', 'Gallery.Gallery');

Croogo::hookHelper('*', 'Gallery.Gallery');

Croogo::hookAdminMenu('Gallery');

$cacheConfig = array(
	'duration' => '+1 hour',
	'engine' => Configure::read('Cache.defaultEngine'),
);
Cache::config('gallery', $cacheConfig);

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
			'url' => '#',
			'children' => array(
				'list' => array(
					'title' => __d('gallery', 'List albums'),
					'url' => array(
						'plugin' => 'gallery',
						'admin' => true,
						'controller' => 'albums',
						'action' => 'index',
					),
				),
				'new' => array(
					'title' => __d('gallery', 'New album'),
					'url' => array(
						'plugin' => 'gallery',
						'admin' => true,
						'controller' => 'albums',
						'action' => 'add',
					),
				),
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
			'title' => __d('gallery', 'Gallery settings'),
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

if (!CakePlugin::loaded('Imagine')) {
	CakePlugin::load('Imagine', array('bootstrap' => true));
}

App::uses('StorageManager', 'Assets.Lib');
StorageManager::config('Gallery', array(
	'adapterOptions' => array(WWW_ROOT . 'galleries', true),
	'adapterClass' => '\Gaufrette\Adapter\Local',
	'class' => '\Gaufrette\Filesystem',
));
