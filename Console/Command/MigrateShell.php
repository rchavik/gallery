<?php

App::uses('AppShell', 'Console/Command');

class MigrateShell extends AppShell {

	public $uses = array(
		'Gallery.MigratePhoto',
		'Assets.AssetsAttachment',
		'Assets.AssetsAsset',
	);

	public function getOptionParser() {
		return parent::getOptionParser()
			->addOptions(array(
				'title' => array(
					'help' => 'Album title',
					'required' => false,
					'short' => 't',
				),
			));
	}

	public function photos() {
		$photos = $this->MigratePhoto->find('all');
		foreach ($photos as $photo) {
			$this->_migratePhoto($photo);
		}
	}

	protected function _createAssetFromFile($path) {
		$fp = fopen($path, 'r');
		$stat = fstat($fp);
		fclose($fp);
		$imageInfo = getimagesize($path);
		$pathinfo = pathinfo($path);

		$relativePath = '/' . str_replace(WWW_ROOT, '', $path);

		return $this->AssetsAttachment->AssetsAsset->create(array(
			'model' => 'AssetsAttachment',
			'filename' => $pathinfo['basename'],
			'filesize' => $stat[7],
			'width' => $imageInfo[0],
			'height' => $imageInfo[1],
			'mime_type' => $imageInfo['mime'],
			'extension' => $pathinfo['extension'],
			'hash' => sha1_file($path),
			'path' => $relativePath,
			'adapter' => 'Gallery',
			'params' => 'type=original',
		));
	}

	protected function _createChildAssetFromFile($path, $attachment, $params = null) {
		$asset = $this->_createAssetFromFile($path);
		$asset['AssetsAsset']['parent_asset_id'] = $attachment['AssetsAsset']['id'];
		$asset['AssetsAsset']['model'] = $attachment['AssetsAsset']['model'];
		$asset['AssetsAsset']['foreign_key'] = $attachment['AssetsAsset']['foreign_key'];
		$asset['AssetsAsset']['params'] = $params;
		return $this->AssetsAttachment->AssetsAsset->save($asset);
	}

	protected function _migratePhoto($photo) {
		$path = WWW_ROOT . $photo['Photo']['original'];
		$attachment = $this->AssetsAttachment->createFromFile($path);

		if (is_string($attachment)) {
			$this->err($attachment);
			$this->_stop();
		}

		$asset = $this->_createAssetFromFile($path);
		$attachment['AssetsAsset'] = $asset['AssetsAsset'];
		$saved = $this->AssetsAttachment->saveAll($attachment);

		if (!$saved) {
			$this->err('Unable to save attachment: ' . $path);
			$this->_stop();
		}

		$attachmentId = $this->AssetsAttachment->id;

		$attachment = $this->AssetsAttachment->findById($attachmentId);
		$originalAsset = array('AssetsAsset' => $attachment['AssetsAsset']);

		$path = WWW_ROOT . $photo['Photo']['small'];
		$smallAsset = $this->_createChildAssetFromFile($path, $attachment, 'type=small');

		$path = WWW_ROOT . $photo['Photo']['large'];
		$largeAsset = $this->_createChildAssetFromFile($path, $attachment, 'type=large');

		$data = array(
			'Photo' => array(
				'id' => $photo['Photo']['id'],
				'attachment_id' => $attachmentId,
				'original_id' => $originalAsset['AssetsAsset']['id'],
				'small_id' => $smallAsset['AssetsAsset']['id'],
				'large_id' => $largeAsset['AssetsAsset']['id'],
			),
		);
		$fields = array('attachment_id', 'original_id', 'small_id', 'large_id');
		$this->MigratePhoto->save($data, false, $fields);

		$foreignKey = $photo['Album'][0]['id'];
		$type = Inflector::camelize($photo['Album'][0]['title']);
		$this->_saveUsage($originalAsset, 'Album', $foreignKey, 'original');
		$this->_saveUsage($smallAsset, 'Album', $foreignKey, 'small');
		$this->_saveUsage($largeAsset, 'Album', $foreignKey, 'large');

	}

	protected function _saveUsage($asset, $model, $foreignKey, $type = null) {
		$asset['AssetsAssetUsage'] = array(
			array(
				'model' => $model,
				'foreign_key' => $foreignKey,
				'type' => $type,
			),
		);
		return $this->AssetsAsset->saveAll($asset);
	}

}
