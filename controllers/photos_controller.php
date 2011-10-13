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

	var $actsAs = array(
		'Containable',
		);

	function admin_edit($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Photo.', true));
			$this->redirect(array('action' => 'index'));
		}
		$photo = $this->Photo->findById($id);
		$albumId = $this->Photo->Album->findById($photo['Photo']['album_id']);
		$redirect = array('controller' => 'albums', 'action' => 'upload', $albumId['Album']['id']);
		if (!empty($this->data)) {
			if ($this->Photo->save($this->data)) {
				$this->Session->setFlash(__d('gallery', 'Photo is saved.', true));
				$this->redirect($redirect);
			} else {
				$this->Session->setFlash(__d('gallery', 'Photo is saved.', true));
			}
		}
		$albums = $this->Photo->Album->find('list');

		$this->set('title_for_layout',__d('gallery', 'Edit Photo', true));
		$this->data = $this->Photo->read(null, $id);
		$this->set(compact(array('albums')));
	}
}
?>
