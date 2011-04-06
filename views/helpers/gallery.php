<?php
/**
 * Gallery Helper
 *
 *
 * @category Helper
 * @package  Croogo
 * @version  1.3.2
 * @author   Edinei L. Cipriani <phpedinei@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.edineicipriani.com.br
 */
class GalleryHelper extends AppHelper {

/**
 * Other helpers used by this helper
 *
 * @var array
 * @access public
 */

    var $helpers = array(
        'Layout'
    );


/**
 * Called after LayoutHelper::setNode()
 *
 * @return void
 */
	public function afterRender() {
		if(ClassRegistry::getObject('view')){
			echo $this->Layout->View->element('gallery_include_js', array('plugin' => 'gallery'));
		}
    }


/**
 * Called after LayoutHelper::nodeBody()
 *
 * @return string
 */
	public function afterSetNode() {
		$this->Layout->setNodeField('body', preg_replace_callback('/\[Gallery:.*\]/',array(&$this,'replaceForAlbum'), $this->Layout->node('body')));
	}

	public function replaceForAlbum($subject){
		preg_match('/\[Gallery:(.*)\]/', $subject[0], $matches);
		return  $this->Layout->View->element('gallery_album', array('plugin' => 'gallery', 'slug' => $matches[1], 'cache' => array('key' => $matches[1], 'time' => '5 mins')));
	}

}

?>