<?php

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {

/**
 * Save the file to the specified path
 * @return boolean TRUE on success
 */
	public function save($path) {
		$input = fopen("php://input", "r");
		$temp = tmpfile();
		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);

		if ($realSize != $this->getSize()) {
			return false;
		}

		$target = fopen($path, "w");
		fseek($temp, 0, SEEK_SET);
		stream_copy_to_stream($temp, $target);
		fclose($target);

		return true;
	}

	public function getTempfile() {
		$input = fopen("php://input", "r");
		$tmpName = tempnam('/tmp', 'qq');
		$temp = fopen($tmpName, 'w');

		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);
		return $tmpName;
	}

	public function getName() {
		return $_GET['qqfile'];
	}

	public function getSize() {
		if (isset($_SERVER["CONTENT_LENGTH"])) {
			return (int)$_SERVER["CONTENT_LENGTH"];
		} else {
			throw new Exception('Getting content length is not supported.');
		}
	}

}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {

/**
 * Save the file to the specified path
 * @return boolean TRUE on success
 */
	public function save($path) {
		if (!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)) {
			return false;
		}
		return true;
	}

	public function getName() {
		return $_FILES['qqfile']['name'];
	}

	public function getSize() {
		return $_FILES['qqfile']['size'];
	}

}

class qqFileUploader {

	private $allowedExtensions = array();

	private $sizeLimit = 10485760;

	private $file;

	public function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760) {
		$allowedExtensions = array_map("strtolower", $allowedExtensions);

		$this->allowedExtensions = $allowedExtensions;
		$this->sizeLimit = $sizeLimit;

		if (isset($_GET['qqfile'])) {
			$this->file = new qqUploadedFileXhr();
		} elseif (isset($_FILES['qqfile'])) {
			$this->file = new qqUploadedFileForm();
		} else {
			$this->file = false;
		}
	}

/**
 * Returns array('success'=>true) or array('error'=>'error message')
 */
	public function handleUpload($uploadDirectory, $replaceOldFile = false) {
		if (!is_writable($uploadDirectory)) {
			return array('error' => "Server error. Upload directory isn't writable.");
		}

		if (!$this->file) {
			return array('error' => 'No files were uploaded.');
		}

		$size = $this->file->getSize();

		if ($size == 0) {
			return array('error' => 'File is empty');
		}

		if ($size > $this->sizeLimit) {
			return array('error' => 'File is too large');
		}

		$filename = $this->file->getName();
		$pathinfo = pathinfo($filename);
		$ext = $pathinfo['extension'];

		if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)) {
			$these = implode(', ', $this->allowedExtensions);
			return array('error' => 'File has an invalid extension, it should be one of ' . $these . '.');
		}

		if (!$replaceOldFile) {
			/// don't overwrite previous files that were uploaded
			while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
				$filename .= rand(10, 99);
			}
		}

		$tmpName = $this->file->getTempfile();
		$hash = sha1_file($tmpName);

		$Attachment = ClassRegistry::init('Assets.AssetsAttachment');
		$attachment = $Attachment->create(array(
			'title' => $this->file->getName(),
			'slug' => Inflector::slug(strtolower($filename)),
			'body' => $filename,
			'status' => true,
			'sticky' => false,
			'plugin' => 'Gallery',
			'hash' => $hash,
		));

		$attachment['AssetsAsset'] = array(
			'file' => array(
				'name' => $this->file->getName(),
				'type' => $ext,
				'size' => $this->file->getSize(),
				'tmp_name' => $tmpName,
				'error' => UPLOAD_ERR_OK,
			),
			'model' => 'AssetsAttachment',
			'extension' => $ext,
			'hash' => $hash,
			'adapter' => 'Gallery',
			'uploadDirectory' => basename(dirname($uploadDirectory)),
		);

		$uploadDirectory = dirname($uploadDirectory);

		$saved = ($Attachment->saveAll($attachment));
		if (file_exists($tmpName)) {
			unlink($tmpName);
		}
		if ($saved) {
			$Attachment->contain(array('AssetsAsset'));
			$attachment = $Attachment->findById($Attachment->id);
			$uploadedPath = $attachment['AssetsAsset']['path'];
			return array(
				'file' => $uploadedPath,
				'attachment_id' => $attachment['AssetsAttachment']['id'],
				'asset_id' => $attachment['AssetsAsset']['id'],
				'success' => true,
			);
		} else {
			return array('error' => 'Could not save uploaded file.' .
				'The upload was cancelled, or server error encountered');
		}
	}

}
