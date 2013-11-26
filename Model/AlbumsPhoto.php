<?php

App::uses('GalleryAppModel', 'Gallery.Model');

class AlbumsPhoto extends GalleryAppModel {

	public $actsAs = array(
		'Croogo.Ordered' => array(
			'field' => 'weight',
			'foreign_key' => 'album_id',
		),
	);

	protected function _addUsage($albumId, $assetId, $type) {
		static $Usage = null;
		if ($Usage === null) {
			$Usage = ClassRegistry::init('Assets.AssetsAssetUsage');
		}
		$count = $Usage->find('count', array(
			'conditions' => array(
				'AssetsAssetUsage.asset_id' => $assetId,
				'AssetsAssetUsage.model' => 'Album',
				'AssetsAssetUsage.foreign_key' => $albumId,
			),
		));
		if ($count == 0) {
			$data = $Usage->create(array(
				'asset_id' => $assetId,
				'model' => 'Album',
				'foreign_key' => $albumId,
				'type' => $type,
			));
			$Usage->save($data);
		}
	}

	public function afterSave($created, $options = array()) {
		$Photo = ClassRegistry::init('Gallery.Photo');
		$photo = $Photo->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'id' => $this->data['AlbumsPhoto']['photo_id'],
			),
		));
		$albumId = $this->data[$this->alias]['album_id'];
		$this->_addUsage($albumId, $photo['Photo']['original_id'], 'original');
		$this->_addUsage($albumId, $photo['Photo']['large_id'], 'large');
		$this->_addUsage($albumId, $photo['Photo']['small_id'], 'small');
	}

}
