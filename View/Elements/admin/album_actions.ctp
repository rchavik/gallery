<div class="photo-actions span3">
<?php
	echo $this->Form->postLink(__d('gallery', 'remove'), 'javascript:;',
		array(
			'rel' => $photo['id'],
			'class' => 'remove',
			'icon' => 'trash',
			'iconSize' => 'small',
		)
	);
?>

<?php
	echo $this->Html->link(__d('gallery', 'edit'), array(
		'controller' => 'photos',
		'action' => 'edit',
		$photo['id'],
	), array(
		'class' => 'edit',
		'icon' => 'edit',
		'iconSize' => 'small',
	));

?>

<?php
	echo $this->Html->link('up', array(
		'controller' => 'photos',
		'action' => 'moveup',
		$photo['AlbumsPhoto']['id'],
	), array(
		'class' => 'up',
		'icon' => 'chevron-up',
		'iconSize' => 'small',
	));
?>

<?php
	echo $this->Html->link('down', array(
		'controller' => 'photos',
		'action' => 'movedown',
		$photo['AlbumsPhoto']['id'],
	), array(
		'class' => 'down',
		'icon' => 'chevron-down',
		'iconSize' => 'small',
	));
?>
</div>
