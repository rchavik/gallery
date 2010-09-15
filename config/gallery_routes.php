<?php
CroogoRouter::connect('/gallery', array('plugin' => 'gallery', 'controller' => 'albums', 'action' => 'index'));
    CroogoRouter::connect('/gallery/albums', array('plugin' => 'gallery', 'controller' => 'albums', 'action' => 'index'));
	CroogoRouter::connect('/gallery/album/:slug', array('plugin' => 'gallery', 'controller' => 'albums', 'action' => 'view'), array('pass' => array('slug')));
?>