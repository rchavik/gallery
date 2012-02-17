<div class="photos index">
    <h2><?php echo __('Photos'); ?></h2>

    <table cellpadding="0" cellspacing="0">
    <?php
        $tableHeaders = $this->Html->tableHeaders(array(
            $this->Paginator->sort('id'),
            '&nbsp;',
            __('Album'),
            __('Title'),
            __('Weight'),
            __('Description'),
            __('url'),
            __('Actions'),
        ));
        echo $tableHeaders;

        $rows = array();
        foreach ($photos AS $attachment) {
            $actions  = $this->Html->link(__('Edit'), array('controller' => 'photos', 'action' => 'edit', $attachment['Photo']['id']));
            $actions .= ' ' . $this->Html->link(__('Delete'), array('controller' => 'photos', 'action' => 'delete', $attachment['Photo']['id']));
			$actions .= ' ' . $this->Layout->adminRowActions($attachment['Photo']['id']);


            $thumbnail = $this->Html->image('/' . $attachment['Photo']['small']);

            $rows[] = array(
				$attachment['Photo']['id'],
				$thumbnail,
				$attachment['Album']['title'],
				$attachment['Photo']['weight'],
				$attachment['Photo']['title'],
				$attachment['Photo']['description'],
				$attachment['Photo']['url'],
				$actions
				);
        }

        echo $this->Html->tableCells($rows);
        echo $tableHeaders;
    ?>
    </table>
</div>

<div class="paging"><?php echo $this->Paginator->numbers(); ?></div>
<div class="counter"><?php echo $this->Paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'))); ?></div>
