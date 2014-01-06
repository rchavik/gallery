<?php

class ToHbtmTask extends AppShell {

	public $uses = array(
		'Gallery.Album',
		'Gallery.Photo',
		'Gallery.AlbumsPhotos',
		);

	public function execute() {
		$this->_exportPhotoId();
	}

	public function _exportPhotoId() {
		$photos = $this->Photo->find('all', array(
			'fields' => array('id', 'album_id'),
			));

		foreach ($photos as $photo) {
		$db = $this->AlbumsPhotos->getDataSource();
		$exist = $this->AlbumsPhotos->findByPhotoId($photo['Photo']['id']);
			if (!$exist) {
				$db->begin();
				$data = $this->AlbumsPhotos->create(array(
					'photo_id' => $photo['Photo']['id'],
					'album_id' => $photo['Photo']['album_id'],
					'master' => 1,
				));
				if($saved = $this->AlbumsPhotos->save($data)) {
					$this->out('Data created');
					$db->commit();;
				} else {
					$db->rollback();
					$this->out('Data failed');
				}
			} else {
				$this->out('Already exist');
			}
		}
		$db->commit();;
	}
}
?>
