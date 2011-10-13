<div class="links form">
<h2><?php echo $title_for_layout; ?></h2>
	<?php echo $this->Form->create('Photo');?>
	<fieldset>
	<?php
		echo $form->input('id');
		echo $form->input('album_id', array(
			'label' => __('album', true),
			'options' => $albums,
			'empty' => false,
		));
		echo $form->input('title');
		echo $form->input('small', array('type' => 'hidden'));
		echo $form->input('large', array('type' => 'hidden'));
		echo $form->input('description', array('label' => __('Description', true)));
	?>
	</fieldset>
	<?php echo $form->end('Submit');?>
</div>
