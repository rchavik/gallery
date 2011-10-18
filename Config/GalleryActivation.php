<?php
/**
 * Example Activation
 *
 * Activation class for Example plugin.
 * This is optional, and is required only if you want to perform tasks when your plugin is activated/deactivated.
 *
 * @package  Croogo
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class GalleryActivation {

	public function beforeActivation(&$controller) {
		return true;
	}

/**
 * Called after activating the plugin in ExtensionsPluginsController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
	public function onActivation(&$controller) {
		// ACL: set ACOs with permissions
		$controller->Croogo->addAco('Gallery/Albums/index', array('registered', 'public'));
		$controller->Croogo->addAco('Gallery/Albums/view', array('registered', 'public'));
		$controller->Croogo->addAco('Gallery/Albums/admin_index', array('admin'));
		$controller->Croogo->addAco('Gallery/Albums/admin_add', array('admin'));
		$controller->Croogo->addAco('Gallery/Albums/admin_edit', array('admin'));
		$controller->Croogo->addAco('Gallery/Photos/admin_upload', array('admin','public','registered'));




		$controller->Setting->write('Gallery.album_limit_pagination', '10', array('editable' => 1, 'title' => 'Albums Per Page'));
		$controller->Setting->write('Gallery.max_width', '500', array('editable' => 1));
		$controller->Setting->write('Gallery.max_width_thumb', '120', array('editable' => 1));
		$controller->Setting->write('Gallery.max_height_thumb', '80', array('editable' => 1));
		$controller->Setting->write('Gallery.quality', '90', array('editable' => '1'));
		$controller->Setting->write('Gallery.jslibs', 'galleria,nivo-slider,DDSlider,pikachoose,fancybox', array('editable' => '1'));
	}

	public function beforeDeactivation(&$controller) {
		return true;
	}

/**
 * Called after deactivating the plugin in ExtensionsPluginsController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
	public function onDeactivation(&$controller) {
		$controller->Croogo->removeAco('Gallery');

		$controller->Setting->deleteKey('Gallery.album_limit_pagination');
		$controller->Setting->deleteKey('Gallery.max_width');
		$controller->Setting->deleteKey('Gallery.max_width_thumb');
		$controller->Setting->deleteKey('Gallery.max_height_thumb');
		$controller->Setting->deleteKey('Gallery.quality');
		$controller->Setting->deleteKey('Gallery.jslibs');
	}
}
?>