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

if (!CakePlugin::loaded('Imagine')) {
	CakePlugin::load('Imagine', array('bootstrap' => true));
}

if (CakePlugin::loaded('Assets')) {
	App::uses('StorageManager', 'Assets.Lib');
	if (class_exists('StorageManager')) {
		StorageManager::config('Gallery', array(
			'adapterOptions' => array(WWW_ROOT . 'galleries', true),
			'adapterClass' => '\Gaufrette\Adapter\Local',
			'class' => '\Gaufrette\Filesystem',
		));
	} else {
		CakeLog::critical('StorageManager class not found. Gallery plugin now requires Assets plugin');
	}
}
