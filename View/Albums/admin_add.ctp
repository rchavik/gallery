<div class="albums form">
	<h2><?php echo __d('gallery','Add album'); ?></h2>
	<?php echo $this->Form->create('Album');?>
	<fieldset>
	<?php
		echo $this->Form->input('title',array('label' => __('Title')));
		echo $this->Form->input('slug');
		echo $this->Form->input('description',array('label' => __('Description')));
		echo $this->Form->input('type',array('label' => __('Type')));
		echo $this->Form->input('params',array('label' => __('Parameters')));
		echo $this->Form->input('status');
	?>
	</fieldset>
	<?php echo $this->Form->end('Submit');?>
</div>