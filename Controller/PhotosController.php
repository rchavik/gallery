<?php

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

	public function admin_index() {
		$photos = $this->paginate();
		$this->set(compact('photos'));
	}

	public function admin_edit($id) {
		$this->Photo->id = $id;
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Photo->save($this->request->data)) {
				$this->Session->setFlash(__d('gallery', 'Photo has been saved.'));
				$this->Croogo->redirect(array('action' => 'edit', $id));
			} else {
				$this->Session->setFlash(__d('gallery', 'Photo cannot be saved.'));
			}
		}
		$this->request->data = $this->Photo->read(null, $id);
		$albums = $this->Photo->Album->find('list');
		$this->set(compact('albums'));
	}

}