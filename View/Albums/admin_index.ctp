<div class="users index">
    <h2><?php echo $title_for_layout; ?></h2>

    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__d('gallery','New album'), array('action'=>'add')); ?></li>
        </ul>
    </div>

    <table cellpadding="0" cellspacing="0">
    <?php
        $tableHeaders =  $this->Html->tableHeaders(array(
			$this->Paginator->sort('id'),
			__d('gallery','Order number'),
			__d('gallery', 'Title'),
			__d('gallery', 'Description'),
			__d('gallery', 'Type'),
			$this->Paginator->sort('status'),
			__d('gallery', 'Actions'),
			));
        echo $tableHeaders;

        $rows = array();
        foreach ($albums as $album) {
			$actions  = $this->Html->link(__('Move up'), array('controller' => 'albums', 'action' => 'moveup', $album['Album']['id']));
			$actions .= ' ' . $this->Html->link(__('Move down'), array('controller' => 'albums', 'action' => 'movedown', $album['Album']['id']));
			$actions .= ' ' . $this->Html->link(__d('gallery','Photos in album'), array('controller' => 'albums', 'action' => 'upload', $album['Album']['id']));
			$actions .= ' ' . $this->Layout->adminRowActions($album['Album']['id']);
            $actions .= ' ' . $this->Html->link(__('Edit'), array('controller' => 'albums', 'action' => 'edit', $album['Album']['id']));
            $actions .= ' ' . $this->Form->postLink(__('Delete'), array('controller' => 'albums', 'action' => 'delete', $album['Album']['id']), null, __('Are you sure you want to delete this album?'));

            $rows[] = array(
				$album['Album']['id'],
				$album['Album']['position'],
				$album['Album']['title'],
				$this->Text->truncate($album['Album']['description'], 50),
				$album['Album']['type'],
				$this->Layout->status($album['Album']['status']),
				$actions,
				);
        }

        echo $this->Html->tableCells($rows);
        echo $tableHeaders;
    ?>
    </table>
</div>

<div class="paging"><?php echo $this->Paginator->numbers(); ?></div>
<div class="counter"><?php echo $this->Paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'))); ?></div>