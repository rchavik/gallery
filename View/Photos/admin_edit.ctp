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
		$this->Html->link(
			$this->Html->image('/'. $this->data['Photo']['small']),
			'/' . $this->data['Photo']['large'],
			array('class' => 'thickbox', 'escape' => false)
		)
	);
	echo $this->Form->input('Album');
	echo $this->Form->input('title');
	echo $this->Form->input('description');
	echo $this->Form->input('url');
	echo $this->Form->input('params');
	echo $this->Form->input('weight');
	echo $this->Form->input('status');
	?>
	</fieldset>
	<div class="buttons">
	<?php
		echo $this->Form->submit(__('Apply'), array('name' => 'apply'));
		echo $this->Form->end(__('Submit'));
		echo $this->Html->link(__('Cancel'), array('controller' => 'photos', 'action' => 'index'), array('class' => 'cancel'));
	?>
	</div>
</div>
