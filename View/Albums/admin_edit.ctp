<div class="albums form">
	<h2><?php echo __d('gallery','Edit album'); ?></h2>

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__d('gallery','Photos'), array('action'=>'upload', $this->data['Album']['id'])); ?></li>
        </ul>
    </div>

	<?php echo $this->Form->create('Album');?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('title',array('label' => __('Title')));
		echo $this->Form->input('slug');
		echo $this->Form->input('description',array('label' => __('Description')));
		echo $this->Form->input('type',array('label' => __('Type')));
		echo $this->Form->input('params',array('label' => __('Parameters')));
		echo $this->Form->input('status');
	?>
	</fieldset>
	<div class="buttons">
	<?php
		echo $this->Form->end(__('Submit'));
		echo $this->Html->link(__('Cancel'), array('action' => 'index'), array('class' => 'cancel'));
	?>
	</div>
</div>