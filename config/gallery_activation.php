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
/**
 * onActivate will be called if this returns true
 *
 * @param  object $controller Controller
 * @return boolean
 */
    public function beforeActivation(&$controller) {
       		$sql = file_get_contents(APP.'plugins'.DS.'gallery'.DS.'config'.DS.'gallery.sql');
	        if(!empty($sql)){
	        	App::import('Core', 'File');
	        	App::import('Model', 'ConnectionManager');
	        	$db = ConnectionManager::getDataSource('default');

	        	$statements = explode(';', $sql);

		        foreach ($statements as $statement) {
		            if (trim($statement) != '') {
		                $db->query($statement);
		            }
		        }
	        }
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
		$controller->Croogo->addAco('Albums');
        $controller->Croogo->addAco('Albums/index', array('registered', 'public')); 
        $controller->Croogo->addAco('Albums/view', array('registered', 'public'));
        $controller->Croogo->addAco('Albums/admin_index', array('admin'));
		$controller->Croogo->addAco('Albums/admin_add', array('admin'));
		$controller->Croogo->addAco('Albums/admin_edit', array('admin'));
		$controller->Croogo->addAco('Photos');
		$controller->Croogo->addAco('Photos/admin_upload', array('admin','public','registered'));



		
		$controller->Setting->write('Gallery.album_limit_pagination', '10', array('editable' => 1, 'title' => 'Albums Per Page'));
		$controller->Setting->write('Gallery.max_width', '500', array('editable' => 1));
    	$controller->Setting->write('Gallery.max_width_thumb', '120', array('editable' => 1));
    	$controller->Setting->write('Gallery.max_height_thumb', '80', array('editable' => 1));
    	$controller->Setting->write('Gallery.quality', '90', array('editable' => '1'));
    }
/**
 * onDeactivate will be called if this returns true
 *
 * @param  object $controller Controller
 * @return boolean
 */
    public function beforeDeactivation(&$controller) {



			$sql = file_get_contents(APP.'plugins'.DS.'gallery'.DS.'config'.DS.'gallery_deactivate.sql');
	        if(!empty($sql)){
	        	App::import('Core', 'File');
	        	App::import('Model', 'ConnectionManager');
	        	$db = ConnectionManager::getDataSource('default');
	        	$statements = explode(';', $sql);

		        foreach ($statements as $statement) {
		            if (trim($statement) != '') {
		                $db->query($statement);
		            }
		        }
	        }
        return true;
    }
/**
 * Called after deactivating the plugin in ExtensionsPluginsController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
    public function onDeactivation(&$controller) {
        $controller->Croogo->removeAco('Albums'); // ExampleController ACO and it's actions will be removed
        $controller->Croogo->removeAco('Photos');


        // Routes: remove

		
		$controller->Setting->deleteKey('Gallery.album_limit_pagination');
		$controller->Setting->deleteKey('Gallery.max_width');
    	$controller->Setting->deleteKey('Gallery.max_width_thumb');
    	$controller->Setting->deleteKey('Gallery.max_height_thumb');
    	$controller->Setting->deleteKey('Gallery.quality');
    }
}
?>