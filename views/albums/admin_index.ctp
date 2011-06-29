<div class="users index">
    <h2><?php echo $title_for_layout; ?></h2>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__d('gallery','New album', true), array('action'=>'add')); ?></li>
        </ul>
    </div>

    <table cellpadding="0" cellspacing="0">
    <?php
        $tableHeaders =  $html->tableHeaders(array(
			$paginator->sort('id'),
			__d('gallery','Order number', true),
			__d('gallery', 'Title', true),
			__d('gallery', 'Description', true),
			__d('gallery', 'Type', true),
			$paginator->sort('status'),
			__d('gallery', 'Actions', true),
			));
        echo $tableHeaders;

        $rows = array();
        foreach ($albums as $album) {
        	$actions  = $html->link(__('Move up', true), array('controller' => 'albums', 'action' => 'moveup', $album['Album']['id']));
            $actions .= ' ' . $html->link(__('Move down', true), array('controller' => 'albums', 'action' => 'movedown', $album['Album']['id']));
           	$actions .= ' ' . $html->link(__d('gallery','Photos in album', true), array('controller' => 'albums', 'action' => 'upload', $album['Album']['id']));
			$actions .= ' ' . $layout->adminRowActions($album['Album']['id']);
            $actions .= ' ' . $html->link(__('Edit', true), array('controller' => 'albums', 'action' => 'edit', $album['Album']['id']));
            $actions .= ' ' . $html->link(__('Delete', true), array('controller' => 'albums', 'action' => 'delete', $album['Album']['id']), null, __('Are you sure you want to delete this album?', true));

            $rows[] = array(
				$album['Album']['id'],
				$album['Album']['position'],
				$album['Album']['title'],
				$this->Text->truncate($album['Album']['description'], 50),
				$album['Album']['type'],
				$layout->status($album['Album']['status']),
				$actions,
				);
        }

        echo $html->tableCells($rows);
        echo $tableHeaders;
    ?>
    </table>
</div>

<div class="paging"><?php echo $paginator->numbers(); ?></div>
<div class="counter"><?php echo $paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true))); ?></div>