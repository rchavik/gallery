<div class="albums form">
	<h2><?php echo __d('gallery','Add album'); ?></h2>

	<?php echo $this->Form->create('Album');?>
	<fieldset>
		<div class='tabs'>
			<ul>
				<li><?php echo $this->Html->link('Album', '#album-main'); ?></li>
				<li><?php echo $this->Html->link('Settings', '#album-settings'); ?></li>
				<?php echo $this->Layout->adminTabs(); ?>
			</ul>

			<div id='album-main'>
			<?php
				echo $this->Form->input('title', array('label' => __('Title')));
				echo $this->Form->input('slug');
				echo $this->Form->input('description', array('label' => __('Description')));
				echo $this->Form->input('type', array('label' => __('Type')));
				echo $this->Form->input('status');
			?>
			</div>

			<div id='album-settings'>
			<?php
				echo $this->Form->input('quality');
				echo $this->Form->input('max_width');
				echo $this->Form->input('max_height');
				echo $this->Form->input('max_width_thumbnail');
				echo $this->Form->input('max_height_thumbnail');
				echo $this->Form->input('params', array('label' => __('Parameters')));
			?>
			</div>

		</div>
	</fieldset>

	<div class="buttons">
	<?php
		echo $this->Form->end(__('Submit'));
		echo $this->Html->link(__('Cancel'), array('action' => 'index'), array('class' => 'cancel'));
	?>
	</div>
</div>