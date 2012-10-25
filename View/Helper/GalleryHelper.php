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
		'Html',
		'Layout'
	);

/**
 * A list of gallery/library helpers
 */
	var $_jslibs = array();

	function __construct(View $View, $settings = array()) {
		$jslibs = Configure::read('Gallery.jslibs');
		$jslibs = explode(',', $jslibs);
		$helpers = array();
		foreach ($jslibs as $jslib) {
			$class = $this->__helperClassName($jslib);
			$helpers[] = 'Gallery.' . $class;
			$this->_jslibs[] = $class;
		}
		$this->helpers = Set::merge($this->helpers, $helpers);
		parent::__construct($View, $settings);
	}

/**
 * Include library css/javascript assets into the document
 */
	public function assets($options = array()) {
		if (Configure::read('Site.status') == 0 ||
		    Configure::read('Gallery.assets') === false ||
		    isset($this->_View->params['admin'])
		) {
			return;
		}
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
		if (!empty($this->_View)) {
			if (Configure::read('Site.status') == 0 ||
			    Configure::read('Gallery.assets') === false ||
			    isset($this->_View->params['admin'])
			) {
				return;
			}
			echo $this->assets();
		}
	}

	public function beforeRender() {
		if (isset($this->_View->params['admin'])) {
			$this->Html->css('/gallery/css/gallery', null, array('inline' => false));
			$this->Html->script('Gallery.gallery', array('inline' => false));
		}
	}


/**
 * Called after LayoutHelper::nodeBody()
 *
 * @return string
 */
	public function afterSetNode() {
		$this->Layout->setNodeField('body', preg_replace_callback('/\[Gallery:([a-zA-Z0-9\-]+)\]/',array(&$this,'replaceForAlbum'), $this->Layout->node('body')));
	}

	public function replaceForAlbum($subject){
		preg_match('/\[Gallery:([a-zA-Z0-9\-]+)\]/', $subject[0], $matches);
		return $this->_View->element('gallery_album', array('slug' => $matches[1]), array('plugin' => 'gallery'));
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

	private function __helperClassName($type) {
		$class = Inflector::camelize(strtolower(str_replace('-', '_', $type)));
		return empty($class) ? 'Galleria' : $class;
	}

	public function album($album, $photos) {
		$class = $this->__helperClassName($album['Album']['type']);
		return $this->{$class}->album($album, $photos);
	}

	public function photo($album, $photo) {
		$class = $this->__helperClassName($album['Album']['type']);
		return $this->{$class}->photo($album, $photo);
	}

	public function initialize($album) {
		$class = $this->__helperClassName($album['Album']['type']);
		$this->{$class}->initialize($album);
	}
}

?>