<?php

$this->extend('/Common/admin_index');

$this->assign('actions', '');
?>
<div class="photos index table-container">

	<table class="table table-striped">
	<?php
		$tableHeaders = $this->Html->tableHeaders(array(
			$this->Paginator->sort('id'),
			$this->Paginator->sort('small', __('Preview')),
			$this->Paginator->sort('description', __('Description')),
			$this->Paginator->sort('url', __('url')),
			__('Actions'),
		));
		echo $tableHeaders;

		$rows = array();
		foreach ($photos as $attachment) {
			$actions = array();
			$actions[] = $this->Croogo->adminRowAction(__('Choose'), '#', array(
				'class' => 'item-choose',
				'data-chooser_type' => 'Photo',
				'data-chooser_id' => $attachment['Photo']['id'],
				'data-chooser_small' => $attachment['Photo']['small'],
				'data-chooser_large' => $attachment['Photo']['large'],
				'data-chooser_original' => $attachment['Photo']['original'],
			));
			$actions[] = $this->Croogo->adminRowActions($attachment['Photo']['id']);

			$thumbnail = $this->Html->image(
				'/' . $attachment['Photo']['small'],
				array('class' => 'img-polaroid')
			);

			$rows[] = array(
				$attachment['Photo']['id'],
				$thumbnail,
				$this->Text->truncate(strip_tags($attachment['Photo']['description']), 30),
				$attachment['Photo']['url'],
				implode(' ', $actions),
			);
		}

		echo $this->Html->tableCells($rows);
		echo $tableHeaders;
	?>
	</table>
</div>
