<div class="links form">
	<h2><?php echo __d('gallery', 'Edit Photo'); ?></h2>
	<?php
	echo $this->Form->create('Photo', array(
		'url' => array(
			'controller' => 'photos',
			'action' => 'edit',
			),
		));
	?>
	<fieldset>
	<?php
	echo $this->Form->input('id');
	echo $this->Html->div('input text',
		'<label>Thumbnail</label>' .
		$this->Html->image('/'. $this->data['Photo']['small'])
		);
	echo $this->Form->input('album_id', array(
		'label' => __('Album'),
		'options' => $albums,
		'empty' => false,
	));
	echo $this->Form->input('title');
	echo $this->Form->input('description');
	?>
	</fieldset>
	<?php echo $this->Form->end('Submit');?>
</div>