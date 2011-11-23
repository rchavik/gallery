<?php
/**
 * Routes
 *
 * example_routes.php will be loaded in main app/config/routes.php file.
 */
    Croogo::hookRoutes('Gallery');
/**
 * Behavior
 *
 * This plugin's Example behavior will be attached whenever Node model is loaded.
 */
    //Croogo::hookBehavior('Node', 'Example.Example', array());
/**
 * Component
 *
 * This plugin's Example component will be loaded in ALL controllers.
 */
    Croogo::hookComponent('*', 'Gallery.Gallery');
/**
 * Helper
 *
 * This plugin's Example helper will be loaded via NodesController.
 */
    Croogo::hookHelper('*', 'Gallery.Gallery');
/**
 * Admin menu (navigation)
 *
 * This plugin's admin_menu element will be rendered in admin panel under Extensions menu.
 */
    Croogo::hookAdminMenu('Gallery');
/**
 * Admin row action
 *
 * When browsing the content list in admin panel (Content > List),
 * an extra link called 'Example' will be placed under 'Actions' column.
 */
//    Croogo::hookAdminRowAction('Nodes/admin_index', 'Example', 'plugin:example/controller:example/action:index/:id');
/**
 * Admin tab
 *
 * When adding/editing Content (Nodes),
 * an extra tab with title 'Example' will be shown with markup generated from the plugin's admin_tab_node element.
 *
 * Useful for adding form extra form fields if necessary.
 */
  //  Croogo::hookAdminTab('Nodes/admin_add', 'Example', 'example.admin_tab_node');
  //  Croogo::hookAdminTab('Nodes/admin_edit', 'Example', 'example.admin_tab_node');


$cacheConfig = array(
	'duration' => '+1 hour',
	'engine' => 'File',
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
		'settings' => array(
			'title' => __d('gallery', 'Gallery settings'),
			'url' => array(
				'plugin' => false,
				'admin' => true,
				'controller' => 'settings',
				'action' => 'prefix',
				'Gallery',
				)
			),
		),
	));
