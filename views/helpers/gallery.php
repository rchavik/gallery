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
 * A list of gallery/library helpers
 */
	var $_jslibs = array();

	function __construct() {
		$jslibs = Configure::read('Gallery.jslibs');
		$jslibs = explode(',', $jslibs);
		$helpers = array();
		foreach ($jslibs as $jslib) {
			$class = Inflector::camelize(str_replace('-', '_', $jslib));
			$helpers[] = 'Gallery.' . $class;
			$this->_jslibs[] = $class;
		}
		$this->helpers = Set::merge($this->helpers, $helpers);
		parent::__construct();
	}

/**
 * Include library css/javascript assets into the document
 */
	public function assets($options = array()) {
		foreach ($this->_jslibs as $jslib) {
			$this->{$jslib}->assets($options);
		}
	}

/**
 * Called after LayoutHelper::setNode()
 *
 * @return void
 */
	public function afterRender() {
		if(ClassRegistry::getObject('view')){
			if (isset($this->Layout->View->params['admin'])) {
				return;
			}
			echo $this->assets();
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

	public function getAlbumJsParams($album) {
		if (empty($album['Album']['params'])) return false;
		$params = $album['Album']['params'];
		$params = str_replace("\r", '', $params);
		$config = array();
		$lines = explode("\n", $params);
		for ($i = 0, $ii = count($lines); $i < $ii; $i++) {
			$line = str_replace(' ', '', $lines[$i]);
			parse_str($line, $arr);
			$config += $arr;
		}
		return json_encode($config);
	}

}

?>