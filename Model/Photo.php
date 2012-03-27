<?php
/**
 * Gallery Photo
 *
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
 * Model name
 *
 * @var string
 * @access public
 */
	public $name = 'Photo';

	public $dir = '';

	public $albumDir = '';

	public $actsAs = array(
		'Params',
		);

/**
 * Model associations: belongsTo
 *
 * @var array
 * @access public
 */
	public $belongsTo = array(
		'Album' => array(
			'className' => 'Gallery.Album',
			'foreignKey' => 'album_id'
		)
	);

	public $findMethods = array(
		'by_album' => true,
		);

	function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id = false, $table = null, $ds = null);
		$this->setTargetDirectory();
	}

	function getTargetDirectory() {
		return $this->dir;
	}

	function setTargetDirectory($dir = 'photos', $perm = 0775) {
		if ($dir == 'photos') {
			$this->albumDir = 'img' . DS . $dir . DS;
		} else {
			$this->albumDir = 'galleries' . DS . $dir . DS;
		}
		$this->dir = WWW_ROOT . $this->albumDir;
		$this->sourceDir = WWW_ROOT . $this->albumDir . 'source' . DS;
		if(!is_dir($this->dir)) {
			mkdir($this->dir, $perm, true);
		}
		if(!is_dir($this->sourceDir)) {
			mkdir($this->sourceDir, $perm, true);
		}
	}

	function beforeDelete(){
		$photo = $this->findById($this->id);
		unlink(WWW_ROOT . $photo['Photo']['small']);
		unlink(WWW_ROOT . $photo['Photo']['large']);
		return true;
	}

	function beforeSave($options = array()){
		if ($this->exists()) {
			return true;
		}
		$this->getEventManager()->dispatch(
			new CakeEvent('setupAlbumPath', $this)
			);
		$this->data = $this->_upload($this->data);
		return true;
	}

	public function _findBy_album($state, $query, $results = array()) {
		if ($state == 'before') {
			$slug = isset($query['album']) ? $query['album'] : false;
			$query = Set::merge($query, array(
				'conditions' => array(
					'Photo.status' => true,
					'Album.slug' => $slug,
					'Album.status' => true,
					),
				'order' => 'Photo.weight ASC',
				));
			return $query;
		} else {
			return $results;
		}
	}

	protected function _upload($data){
		$this->Album->recursive = -1;
		$album = $this->Album->read(null, $data['Photo']['album_id']);

		if (!empty($album['Album']['max_width'])) {
			$max_width = $album['Album']['max_width'];
		} else {
			$max_width = Configure::read('Gallery.max_width');
		}

		if (!empty($album['Album']['max_height'])) {
			$max_height = $album['Album']['max_height'];
		} else {
			$max_height = Configure::read('Gallery.max_height');
		}

		if (!empty($album['Album']['max_width_thumbnail'])) {
			$thumb_width = $album['Album']['max_width_thumbnail'];
		} else {
			$thumb_width = Configure::read('Gallery.max_width_thumbnail');
		}

		if (!empty($album['Album']['max_height_thumbnail'])) {
			$thumb_height = $album['Album']['max_height_thumbnail'];
		} else {
			$thumb_height = Configure::read('Gallery.max_height_thumbnail');
		}

		if (!empty($album['Album']['quality'])) {
			$thumb_quality = $album['Album']['quality'];
		} else {
			$thumb_quality = Configure::read('Gallery.quality');
		}

		if (empty($thumb_width) || empty($thumb_height) ||
		    empty($thumb_quality) || empty($max_height) || empty($max_width)) {
			throw new UnexpectedValueException('Missing gallery settings');
		}

		App::import('Vendor', 'Gallery.qqFileUploader', array('file' => 'qqFileUploader.php'));
	   	$uploader = new qqFileUploader();
		$result = $uploader->handleUpload($this->sourceDir);
		if (!empty($result['file'])) {
			$sourceFile = $this->sourceDir.$result['file'];
			$copyFile = $this->dir.$result['file'];
			copy($sourceFile, $copyFile);
		}

		$width = $this->getWidth($this->dir.$result['file']);
		$height = $this->getHeight($this->dir.$result['file']);
		if ($width > $max_width){
			$scale = $max_width/$width;
			$this->resizeImage($this->dir.$result['file'],$width,$height,$scale);
		}else{
			$scale = 1;
			$this->resizeImage($this->dir.$result['file'],$width,$height,$scale);
		}
		if (empty($thumb_height) && !empty($thumb_width)) {
			$this->resizeImage2('resize', $result['file'], $this->dir, 'thumb_'.$result['file'], $thumb_width, FALSE, $thumb_quality);
		}elseif (empty($thumb_width) && !empty($thumb_height)) {
			$this->resizeImage2('resize', $result['file'], $this->dir, 'thumb_'.$result['file'], FALSE, $thumb_height, $thumb_quality);
		}else{
			$this->resizeImage2('resizeCrop', $result['file'], $this->dir, 'thumb_'.$result['file'], $thumb_width, $thumb_height, $thumb_quality);
		}

		$data['Photo']['small'] = $this->albumDir . 'thumb_'.$result['file'];
		$data['Photo']['large'] = $this->albumDir . $result['file'];
		$data['Photo']['original'] = $this->albumDir . 'source' . DS . $result['file'];
		return $data;
	}


	function getHeight($image) {
		$sizes = getimagesize($image);
		return $sizes[1];
	}

	function getWidth($image) {
		$sizes = getimagesize($image);
		return $sizes[0];
	}

	function resizeImage($image,$width,$height,$scale) {
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
		$ext = strtolower(substr(basename($image), strrpos(basename($image), ".") + 1));
		$source = "";
		if($ext == "png"){
			$source = imagecreatefrompng($image);
		}elseif($ext == "jpg" || $ext == "jpeg"){
			$source = imagecreatefromjpeg($image);
		}elseif($ext == "gif"){
			$source = imagecreatefromgif($image);
		}
		imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
		imagejpeg($newImage,$image,Configure::read('Gallery.quality'));
		chmod($image, 0777);
	}

	function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
		$ext = strtolower(substr(basename($image), strrpos(basename($image), ".") + 1));
		$source = "";
		if($ext == "png"){
			$source = imagecreatefrompng($image);
		}elseif($ext == "jpg" || $ext == "jpeg"){
			$source = imagecreatefromjpeg($image);
		}elseif($ext == "gif"){
			$source = imagecreatefromgif($image);
		}
		imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
		imagejpeg($newImage,$thumb_image_name,90);
		chmod($thumb_image_name, 0777);
		return $thumb_image_name;
	}

	function cropImage($thumb_width, $x1, $y1, $x2, $y2, $w, $h, $thumbLocation, $imageLocation){
		$scale = $thumb_width/$w;
		$cropped = $this->resizeThumbnailImage($thumbLocation,$imageLocation,$w,$h,$x1,$y1,$scale);
	}


	function resizeImage2($cType = 'resize', $id, $imgFolder, $newName = false, $newWidth=false, $newHeight=false, $quality = 75, $bgcolor = false)
	{
		$img = $imgFolder . $id;
		list($oldWidth, $oldHeight, $type) = getimagesize($img);
		$ext = $this->image_type_to_extension($type);

		//check to make sure that the file is writeable, if so, create destination image (temp image)
		if (is_writeable($imgFolder))
		{
			if($newName){
				$dest = $imgFolder . $newName;
			} else {
				$dest = $imgFolder . 'tmp_'.$id;
			}
		}
		else
		{
			//if not let developer know
			$imgFolder = substr($imgFolder, 0, strlen($imgFolder) -1);
			$imgFolder = substr($imgFolder, strrpos($imgFolder, '\\') + 1, 20);
			debug("You must allow proper permissions for image processing. And the folder has to be writable.");
			debug("Run \"chmod 777 on '$imgFolder' folder\"");
			exit();
		}

		//check to make sure that something is requested, otherwise there is nothing to resize.
		//although, could create option for quality only
		if ($newWidth OR $newHeight)
		{
			/*
			 * check to make sure temp file doesn't exist from a mistake or system hang up.
			 * If so delete.
			 */
			if(file_exists($dest))
			{
				unlink($dest);
			}
			else
			{
				switch ($cType){
					default:
					case 'resize':
						# Maintains the aspect ration of the image and makes sure that it fits
						# within the maxW(newWidth) and maxH(newHeight) (thus some side will be smaller)
						$widthScale = 2;
						$heightScale = 2;

						if($newWidth) $widthScale = 	$newWidth / $oldWidth;
						if($newHeight) $heightScale = $newHeight / $oldHeight;
						//debug("W: $widthScale  H: $heightScale<br>");
						if($widthScale < $heightScale) {
							$maxWidth = $newWidth;
							$maxHeight = false;
						} elseif ($widthScale > $heightScale ) {
							$maxHeight = $newHeight;
							$maxWidth = false;
						} else {
							$maxHeight = $newHeight;
							$maxWidth = $newWidth;
						}

						if($maxWidth > $maxHeight){
							$applyWidth = $maxWidth;
							$applyHeight = ($oldHeight*$applyWidth)/$oldWidth;
						} elseif ($maxHeight > $maxWidth) {
							$applyHeight = $maxHeight;
							$applyWidth = ($applyHeight*$oldWidth)/$oldHeight;
						} else {
							$applyWidth = $maxWidth;
							$applyHeight = $maxHeight;
						}
						//debug("mW: $maxWidth mH: $maxHeight<br>");
						//debug("aW: $applyWidth aH: $applyHeight<br>");
						$startX = 0;
						$startY = 0;
						//exit();
						break;
					case 'resizeCrop':
						// -- resize to max, then crop to center
						$ratioX = $newWidth / $oldWidth;
						$ratioY = $newHeight / $oldHeight;

						if ($ratioX < $ratioY) {
							$startX = round(($oldWidth - ($newWidth / $ratioY))/2);
							$startY = 0;
							$oldWidth = round($newWidth / $ratioY);
							$oldHeight = $oldHeight;
						} else {
							$startX = 0;
							$startY = round(($oldHeight - ($newHeight / $ratioX))/2);
							$oldWidth = $oldWidth;
							$oldHeight = round($newHeight / $ratioX);
						}
						$applyWidth = $newWidth;
						$applyHeight = $newHeight;
						break;
					case 'crop':
						// -- a straight centered crop
						$startY = ($oldHeight - $newHeight)/2;
						$startX = ($oldWidth - $newWidth)/2;
						$oldHeight = $newHeight;
						$applyHeight = $newHeight;
						$oldWidth = $newWidth;
						$applyWidth = $newWidth;
						break;
				}

				switch($ext)
				{
					case 'gif' :
						$oldImage = imagecreatefromgif($img);
						break;
					case 'png' :
						$oldImage = imagecreatefrompng($img);
						break;
					case 'jpg' :
					case 'jpeg' :
						$oldImage = imagecreatefromjpeg($img);
						break;
					default :
						//image type is not a possible option
						return false;
						break;
				}

				//create new image
				$newImage = imagecreatetruecolor($applyWidth, $applyHeight);

				if($bgcolor):
				//set up background color for new image
					sscanf($bgcolor, "%2x%2x%2x", $red, $green, $blue);
					$newColor = ImageColorAllocate($newImage, $red, $green, $blue);
					imagefill($newImage,0,0,$newColor);
				endif;

				//put old image on top of new image
				imagecopyresampled($newImage, $oldImage, 0,0 , $startX, $startY, $applyWidth, $applyHeight, $oldWidth, $oldHeight);

					switch($ext)
					{
						case 'gif' :
							imagegif($newImage, $dest, $quality);
							break;
						case 'png' :
							imagepng($newImage, $dest, $quality);
							break;
						case 'jpg' :
						case 'jpeg' :
							imagejpeg($newImage, $dest, $quality);
							break;
						default :
							return false;
							break;
					}

				imagedestroy($newImage);
				imagedestroy($oldImage);

				if(!$newName){
					unlink($img);
					rename($dest, $img);
				}

				return true;
			}

		} else {
			return false;
		}


	}

	function image_type_to_extension($imagetype)
	{
	if(empty($imagetype)) return false;
		switch($imagetype)
		{
			case IMAGETYPE_GIF	: return 'gif';
			case IMAGETYPE_JPEG	: return 'jpg';
			case IMAGETYPE_PNG	: return 'png';
			case IMAGETYPE_SWF	: return 'swf';
			case IMAGETYPE_PSD	: return 'psd';
			case IMAGETYPE_BMP	: return 'bmp';
			case IMAGETYPE_TIFF_II : return 'tiff';
			case IMAGETYPE_TIFF_MM : return 'tiff';
			case IMAGETYPE_JPC	: return 'jpc';
			case IMAGETYPE_JP2	: return 'jp2';
			case IMAGETYPE_JPX	: return 'jpf';
			case IMAGETYPE_JB2	: return 'jb2';
			case IMAGETYPE_SWC	: return 'swc';
			case IMAGETYPE_IFF	: return 'aiff';
			case IMAGETYPE_WBMP	: return 'wbmp';
			case IMAGETYPE_XBM	: return 'xbm';
			default				: return false;
		}
	}
}
