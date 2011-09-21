<?php
class OptimizeController extends GalleryAppController {
	var $name = 'Optimize';
	var $uses = array();
	var $dir = '';

	function admin_optimize () {
	}

	function getdata() {
		$this->autoRender = false;
		$dirPath = WWW_ROOT . $this->data['Optimize']['path'];
		$ar = $this->_getDirectorySize($dirPath);
		$size = $this->sizeFormat($ar['size']);
		$numOfFiles = $ar['count'];
		$dircount = $ar['dircount'];
		Configure::write('jpegquality', $this->data['Optimize']['jpgquality']);
		Configure::write('pngquality', $this->data['Optimize']['pngquality']);
		$this->recursedir($dirPath);
	}

	function recursedir($path,$print=true) {
		$dir = 'source';
		if ($handle = opendir($path)) {
			while ($file = readdir($handle)){
				if (is_dir($file)) {
				}
				if ($print)
					echo "\n<li>$path/$file</li>";
					if (! is_dir($path.'/'.$file) && $file != '.' && $file != '..') {
						if (!file_exists($path) && is_dir($path)) {
							mkdir($path . '/' . $dir, 0775, false);
						}
						$source = $path . '/' . $dir . '/';
						$files = $path.'/'.$file;
						copy($files, $source . DS . $file);

						$name = $path.'/'.$file;
						$start = filesize($name);

						if (exif_imagetype($path.'/'.$file) == IMAGETYPE_JPEG) {
							if ($this->optimize_jpeg($path.'/'.$file)) {
								echo 'Optimized';
							} else {
								echo 'Not Optimized';
							}
						} elseif (exif_imagetype($path.'/'.$file) == IMAGETYPE_PNG) {
							$this->optimize_png($path.'/'.$file);
						}
						$end = filesize($path.'/'.$file);
				} elseif (is_dir($path.'/'.$file) && $file != '.' && $file != '..') {
					if ($print) {
						echo "\n<ul>";
					}
					$this->recursedir ($path.'/'.$file);
					if ($print) {
						echo "</ul>";
					}
				}
			}
		}
		return true;
	}

	function optimize_jpeg($file) {
		$jpegConfig = Configure::read('jpegquality');
		if (!$jpegConfig || !is_numeric($jpegConfig)) {
			return false;
		}

		if ($jpegConfig > 100 || $jpegConfig <= 0) {
			$jpegConfig = 80;
		}

		list($w,$h) = @getimagesize($file);
		if(empty($w) || empty($h)) {
			return false;
		}
		$src = imagecreatefromjpeg($file);
		$tmp = imagecreatetruecolor($w,$h);
		imagecopyresampled($tmp,$src,0,0,0,0,$w,$h,$w,$h);
		$src = imagejpeg($tmp,$file,Configure::read('jpegquality'));
		if ($src == true) {
//			$this->log('success');
		} elseif ($src == false) {
//			$this->log('failed');
		}
		imagedestroy($tmp);
		return true;
	}

	function optimize_png($file) {
		$pngConfig = Configure::read('pngquality');
		if (!$pngConfig || !is_numeric($pngConfig)) {
			return false;
		}

		if ($pngConfig > 100 || $pngConfig <= 0) {
			$pngConfig = 80;
		}

		list($w,$h) = @getimagesize($file);
		if(empty($w) || empty($h)) {
			return false;
		}
		$src = imagecreatefrompng($file);
		$tmp = imagecreatetruecolor($w,$h);
		imagecopyresampled($tmp,$src,0,0,0,0,$w,$h,$w,$h);
		$src = imagepng($tmp,$file,$GLOBALS['pngquality']);
		imagedestroy($tmp);
		return true;
	}

/* The getDirectorySize and sizeFormat functions were found at 
http://www.go4expert.com/forums/showthread.php?t=290 
Visit that forum for more info about these functions. */
	function _getDirectorySize($path) { 
		$totalsize = 0; 
		$totalcount = 0; 
		$dircount = 0; 
		if ($handle = opendir ($path)) { 
			while (false !== ($file = readdir($handle))) {
				$nextpath = $path . '/' . $file; 
				if ($file != '.' && $file != '..' && !is_link ($nextpath)) { 
					if (is_dir ($nextpath)) { 
						$dircount++; 
						$result = $this->_getDirectorySize($nextpath); 
						$totalsize += $result['size']; 
						$totalcount += $result['count']; 
						$dircount += $result['dircount']; 
					} elseif (is_file ($nextpath)) {
						$totalsize += filesize ($nextpath); 
						$totalcount++; 
					} 
				} 
			} 
		} 
		closedir ($handle); 
		$total['size'] = $totalsize; 
		$total['count'] = $totalcount; 
		$total['dircount'] = $dircount; 
		return $total; 
	} 

	function sizeFormat($size) {
		if($size < 1024) {
			return $size." bytes"; 

		} else if($size<(1024*1024)) {
			$size=round($size/1024,1); 
			return $size." KB";

		} else if($size<(1024*1024*1024)) {
			$size=round($size/(1024*1024),1); 
			return $size." MB";

		} else {
			$size=round($size/(1024*1024*1024),1); 
			return $size." GB";
		}

	}
}
?>
