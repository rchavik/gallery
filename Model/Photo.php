<?php

App::uses('GalleryAppModel', 'Gallery.Model');

use Imagine\Image\Box;

/**
 * Gallery Photo
 *
 * @category Model
 * @package  Croogo
 * @version  1.3
 * @author   Edinei L. Cipriani <phpedinei@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.edineicipriani.com.br
 */
class Photo extends GalleryAppModel {

/**
 * Album Absolute path
 */
	public $dir = '';

/**
 * Album path
 */
	public $albumDir = '';

/**
 * Behaviors
 */
	public $actsAs = array(
		'Croogo.Params',
		'Imagine.Imagine',
		'Search.Searchable',
	);

	public $filterArgs = array(
		'album_id' => array('type' => 'value'),
		'description' => array('type' => 'like'),
		'url' => array('type' => 'like'),
		'status' => array('type' => 'like'),
	);

/**
 * Model associations: hasAndBelongsToMany
 *
 * @var array
 * @access public
 */
	public $hasAndBelongsToMany = array(
		'Album' => array(
			'className' => 'Gallery.Album',
			'joinTable' => 'albums_photos',
			'foreignKey' => 'photo_id',
			'associationForeignKey' => 'album_id',
			'unique' => 'keepExisting',
			'with' => 'Gallery.AlbumsPhoto',
		)
	);

/**
 * belongsTo relationship
 */
	public $belongsTo = array(
		'AssetsAttachment' => array(
			'className' => 'Assets.AssetsAttachment',
			'foreignKey' => 'attachment_id',
		),
		'OriginalAsset' => array(
			'className' => 'Assets.AssetsAsset',
			'foreignKey' => 'original_id',
		),
		'ThumbnailAsset' => array(
			'className' => 'Assets.AssetsAsset',
			'foreignKey' => 'small_id',
		),
		'LargeAsset' => array(
			'className' => 'Assets.AssetsAsset',
			'foreignKey' => 'large_id',
		),
	);

	public $findMethods = array(
		'by_album' => true,
	);

	public function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id = false, $table = null, $ds = null);
		$this->_loadSettings();
		$this->setTargetDirectory();
	}

	protected function _loadSettings() {
		if (!empty($album['Album']['max_width'])) {
			$this->max_width = $album['Album']['max_width'];
		} else {
			$this->max_width = Configure::read('Gallery.max_width');
		}

		if (!empty($album['Album']['max_height'])) {
			$this->max_height = $album['Album']['max_height'];
		} else {
			$this->max_height = Configure::read('Gallery.max_height');
		}

		if (!empty($album['Album']['max_width_thumbnail'])) {
			$this->thumb_width = $album['Album']['max_width_thumbnail'];
		} else {
			$this->thumb_width = Configure::read('Gallery.max_width_thumbnail');
		}

		if (!empty($album['Album']['max_height_thumbnail'])) {
			$this->thumb_height = $album['Album']['max_height_thumbnail'];
		} else {
			$this->thumb_height = Configure::read('Gallery.max_height_thumbnail');
		}

		if (!empty($album['Album']['quality'])) {
			$this->thumb_quality = $album['Album']['quality'];
		} else {
			$this->thumb_quality = Configure::read('Gallery.quality');
		}
	}


	public function getTargetDirectory() {
		return $this->dir;
	}

	public function setTargetDirectory($dir = 'photos', $perm = 0775) {
		if ($dir == 'photos') {
			$this->albumDir = 'img' . DS . $dir . DS;
		} else {
			$this->albumDir = 'galleries' . DS . $dir . DS;
		}
		$this->dir = WWW_ROOT . $this->albumDir;
		$this->sourceDir = WWW_ROOT . $this->albumDir . 'source' . DS;
		if (!is_dir($this->dir)) {
			mkdir($this->dir, $perm, true);
		}
		if (!is_dir($this->sourceDir)) {
			mkdir($this->sourceDir, $perm, true);
		}
	}

	public function beforeDelete($cascade = true) {
		$photo = $this->findById($this->id);
		return $this->AssetsAttachment->delete($photo['AssetsAttachment']['id']);
	}

	public function beforeSave($options = array()){
		if ($this->exists()) {
			return true;
		}
		$this->getEventManager()->dispatch(
			new CakeEvent('setupAlbumPath', $this)
		);
		$this->data = $this->_upload($this->data);
		return true;
	}

	protected function _findBy_album($state, $query, $results = array()) {
		if ($state == 'before') {
			$slug = isset($query['album']) ? $query['album'] : false;
			$query = Set::merge($query, array(
				'recursive' => -1,
				'fields' => array('*', 'Album.*'),
				'conditions' => array(
					'Photo.status' => true,
					'Album.slug' => $slug,
					'Album.status' => true,
				),
				'joins' => array(
					array(
						'alias' => $this->AlbumsPhoto->alias,
						'table' => $this->AlbumsPhoto->useTable,
						'conditions' => 'Photo.id = AlbumsPhoto.photo_id',
					),
					array(
						'alias' => $this->Album->alias,
						'table' => $this->Album->useTable,
						'conditions' => 'Album.id = AlbumsPhoto.album_id',
					),
				),
				'order' => 'AlbumsPhoto.weight ASC',
			));
			return $query;
		} else {
			return $results;
		}
	}

/**
 * File upload handler
 *
 * When $_FILES['qqfile'] is present, we assume that we are processing browser
 * uploads.  If not, try to use $data['Photo']['original'] as the source, then
 * perform the necessary image processing.
 * @param $data array Photo
 */
	protected function _upload($data){
		$this->Album->recursive = -1;
		$album = $this->Album->read(null, $data['Album']['Album'][0]);

		if (empty($this->thumb_width) || empty($this->thumb_height) ||
		    empty($this->thumb_quality) || empty($this->max_height) || empty($this->max_width)) {
			throw new UnexpectedValueException('Missing gallery settings');
		}

		if (isset($_FILES['qqfile']) || isset($_GET['qqfile'])) {
			// browser uploads
			App::import('Vendor', 'Gallery.qqFileUploader', array('file' => 'qqFileUploader.php'));
			$uploader = new qqFileUploader();
			$result = $uploader->handleUpload($this->sourceDir);
		} else {
			// file probably already processed
			if (!empty($data['Photo']['large'])) {
				return $data;
			}

			// fake upload
			$result = array(
				'file' => $data['Photo']['original'],
				'success' => true,
				);
			$data['Photo']['original'] = null;
			$sourceFile = $this->sourceDir . $result['file'];
			$copyFile = $this->dir . $result['file'];
			copy($sourceFile, $copyFile);
		}

		$large = $this->_createWebFriendlyImage($result['attachment_id']);
		$thumbnail = $this->_createThumbnail($result['attachment_id']);

		$data['Photo']['small_id'] = $thumbnail['AssetsAsset']['id'];
		$data['Photo']['large_id'] = $large['AssetsAsset']['id'];
		$data['Photo']['original_id'] = $result['asset_id'];
		$data['Photo']['attachment_id'] = $result['attachment_id'];
		return $data;
	}

	protected function _createThumbnail($attachmentId) {
		return $this->AssetsAttachment->createResized(
			$attachmentId, $this->thumb_width, $this->thumb_height, array(
				'uploadsDir' => 'galleries',
			)
		);
	}

	protected function _createWebFriendlyImage($attachmentId) {
		return $this->AssetsAttachment->createResized(
			$attachmentId, $this->max_width, null, array(
				'uploadsDir' => 'galleries',
			)
		);
	}

}
