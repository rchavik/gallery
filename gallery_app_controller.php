<?php
class GalleryAppController extends AppController {

	function beforeFilter() {
		Cache::config(array('duration' => '1 hour'));
		if (false === ($setting = Cache::read('Gallery.jslibs'))) {
			$setting = ClassRegistry::init('Setting')->findByKey('Gallery.jslibs');
			Cache::write('Gallery.jslibs', $setting);
		}
		Configure::write('Gallery.jslibs', $setting['Setting']['value']);

		return parent::beforeFilter();
	}

}
?>