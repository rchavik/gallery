<?php

$this->extend('/Common/admin_edit');

$title = $this->data['Photo']['title'] ? $this->data['Photo']['title'] : basename($this->data['Photo']['original']);

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb('Gallery')
	->addCrumb(__d('gallery', 'Photos'), array('admin' => true, 'plugin' => 'gallery', 'controller' => 'photos', 'action' => 'index'))
	->addCrumb($title, $this->here);

echo $this->Form->create('Photo', array(
	'url' => array(
		'controller' => 'photos',
		'action' => 'edit',
	),
));

?>
<div class="row-fluid">
	<div class="span8">

		<ul class="nav nav-tabs">
			<li><a href="#photo-main" data-toggle="tab"><?php echo __d('gallery', 'Photo'); ?></a></li>
			<?php echo $this->Croogo->adminTabs(); ?>
		</ul>

		<div class="tab-content">
			<div id="photo-main" class="tab-pane">
			<?php
				echo $this->Form->input('id');

				echo $this->Form->input('Album');
				$this->Form->inputDefaults(array(
					'label' => false,
					'class' => 'span10',
				));
				echo $this->Form->input('title', array(
					'placeholder' => __d('gallery', 'Title'),
				));
				echo $this->Form->input('description', array(
					'placeholder' => __d('gallery', 'Description'),
				));
				echo $this->Form->input('url', array(
					'placeholder' => __d('gallery', 'Url'),
				));
				echo $this->Form->input('params', array(
					'placeholder' => __d('gallery', 'Params'),
				));
				echo $this->Form->input('weight', array(
					'placeholder' => __d('gallery', 'Weight'),
				));
			?>
			</div>
			<?php echo $this->Croogo->adminTabs(); ?>
		</div>
	</div>

	<div class="span4">
	<?php
		echo $this->Html->beginBox(__('Publishing')) .
			$this->Form->button(__('Apply'), array('name' => 'apply', 'button' => 'default')) .
			$this->Form->button(__('Save'), array('button' => 'primary')) .
			$this->Html->link(__('Cancel'), array('controller' => 'photos', 'action' => 'index'), array('button' => 'danger')) .

			$this->Form->input('status', array(
				'label' => __('Status'),
				'class' => false,
			)) .

			$this->Form->input('created', array(
				'type' => 'text',
				'placeholder' => __('Created'),
				'readonly' => true,
			)) .

			$this->Html->endBox();

		echo $this->Html->beginBox(__('Preview')) .
			$this->Html->link(
				$this->Html->image('/'. $this->data['Photo']['small'], array(
					'class' => 'img-polaroid',
				)),
				'/' . $this->data['Photo']['large'],
				array('class' => 'thickbox', 'escape' => false)
			) .
			$this->Html->endBox();

		echo $this->Croogo->adminBoxes();
	?>
	</div>
</div>
<?php echo $this->Form->end(); ?>
