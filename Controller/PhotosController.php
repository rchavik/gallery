<?php

App::uses('GalleryAppController', 'Gallery.Controller');

/**
 * Gallery Pictures Controller
 *
 * Uploading pictures into gallery, and edit them
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo
 * @version  1.1
 * @author   Zijad Redžić <zijad.redzic@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.demoveo.com
 */
class PhotosController extends GalleryAppController {

	public $components = array(
		'Search.Prg' => array(
			'presetForm' => array(
				'paramType' => 'querystring',
			),
			'commonProcess' => array(
				'paramType' => 'querystring',
			),
		),
	);

	public $presetVars = true;

	public function admin_index() {
		$searchFields = array(
			'album_id' => array('type' => 'text'),
			'description' => array('type' => 'text'),
			'status',
			'url',
		);
		$this->Prg->commonProcess();

		$this->paginate['contain'] = array(
			'OriginalAsset', 'ThumbnailAsset', 'LargeAsset',
		);

		$this->paginate['conditions'] = $this->Photo->parseCriteria($this->passedArgs);
		$photos = $this->paginate();
		$this->set(compact('photos', 'searchFields'));
		if (!empty($this->request->query['chooser'])) {
			$this->layout = 'admin_popup';
			$this->render('admin_chooser');
		}
	}

	public function admin_edit($id) {
		$this->Photo->id = $id;
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Photo->save($this->request->data)) {
				$this->Session->setFlash(__d('gallery', 'Photo has been saved.'), 'default', array('class' => 'success'));
				$this->Croogo->redirect(array('action' => 'edit', $id));
			} else {
				$this->Session->setFlash(__d('gallery', 'Photo cannot be saved.'));
			}
		}
		$this->Photo->recursive = -1;
		$this->Photo->contain(array('Album', 'OriginalAsset', 'ThumbnailAsset', 'LargeAsset'));
		$this->request->data = $this->Photo->read(null, $id);
		$albums = $this->Photo->Album->find('list');
		$this->set(compact('albums'));
	}

	public function admin_moveup($id, $step = 1) {
		if ($this->Photo->AlbumsPhoto->moveUp($id, $step)) {
			$this->Session->setFlash(__('Moved up successfully'), 'default', array('class' => 'success'));
		} else {
			$this->Session->setFlash(__('Could not move up'), 'default', array('class' => 'error'));
		}
		$this->redirect($this->referer());
	}

	public function admin_movedown($id, $step = 1) {
		if ($this->Photo->AlbumsPhoto->moveDown($id, $step)) {
			$this->Session->setFlash(__('Moved down successfully'), 'default', array('class' => 'success'));
		} else {
			$this->Session->setFlash(__('Could not move down'), 'default', array('class' => 'error'));
		}
		$this->redirect($this->referer());
	}

}