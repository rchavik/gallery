<?php

if(!isset($album)):
	$album = $this->requestAction(array(
		'plugin' => 'gallery',
		'controller' => 'albums',
		'action' => 'view'
	), array(
		'pass' => array('slug' => $slug),
	));
endif;

if(!empty($album['Photo'])):

	if (count($album['Photo'])):
		$out = '';
		foreach($album['Photo'] as $photo):
			$out .= $this->Gallery->photo($album, $photo);
		endforeach;
		echo $this->Gallery->album($album, $out);
		$this->Gallery->initialize($album);
	else:
		echo $this->Html->para(null, __d('gallery', 'No photos in the album'));
	endif;

else:
	echo 'Gallery: ' . $slug . ']';
endif;
