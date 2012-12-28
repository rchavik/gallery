<?php

$this->extend('/Common/admin_index');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb('Gallery')
	->addCrumb(__d('gallery', 'Photos'), array('admin' => true, 'plugin' => 'gallery', 'controller' => 'photos', 'action' => 'index'));

$this->assign('actions', ' ');

?>
<div class="photos index table-container">

    <table class="table table-striped">
    <?php
        $tableHeaders = $this->Html->tableHeaders(array(
            $this->Paginator->sort('id'),
            $this->Paginator->sort('small', __('Preview')),
            $this->Paginator->sort('title', __('Title')),
            $this->Paginator->sort('description', __('Description')),
            $this->Paginator->sort('url', __('url')),
            __('Actions'),
        ));
        echo $tableHeaders;

        $rows = array();
        foreach ($photos AS $attachment) {
			$actions = array();
            $actions[] = $this->Croogo->adminRowAction('',
				array('controller' => 'photos', 'action' => 'edit', $attachment['Photo']['id']),
				array('icon' => 'pencil', 'tooltip' => __('Edit'))
			);
			$actions[] = $this->Croogo->adminRowActions($attachment['Photo']['id']);


            $thumbnail = $this->Html->link(
				$this->Html->image('/' . $attachment['Photo']['small'], array(
					'class' => 'img-polaroid',
				)),
				'/' . $attachment['Photo']['large'],
				array('class' => 'thickbox', 'escape' => false)
			);

            $rows[] = array(
				$attachment['Photo']['id'],
				$thumbnail,
				$attachment['Photo']['title'],
				$this->Text->truncate(strip_tags($attachment['Photo']['description']), 30),
				$attachment['Photo']['url'],
				$this->Html->div('item-actions', implode(' ', $actions))
			);
        }

        echo $this->Html->tableCells($rows);
        echo $tableHeaders;
    ?>
    </table>
</div>
