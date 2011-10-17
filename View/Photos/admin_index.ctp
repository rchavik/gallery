<div class="links index">
    <h2><?php echo $title_for_layout; ?></h2>

    <div class="actions">
        <ul>
            <li><?php echo $html->link(__('Add new pictures', true), array('action'=>'add', 'gallery' => $gallery)); ?></li>
        </ul>
    </div>
    <table cellpadding="0" cellspacing="0">
    <?php
        $tableHeaders = $html->tableHeaders(array(
            $paginator->sort('id'),
            '&nbsp;',
            __('Description', true),
            __('Actions', true),
        ));
        echo $tableHeaders;

        $rows = array();
        foreach ($slike AS $attachment) {
            $actions  = $html->link(__('Edit', true), array('controller' => 'gallery_pictures', 'action' => 'edit', $attachment['GalleryPicture']['id'], 'gallery' => $gallery));
            $actions .= ' ' . $html->link(__('Delete', true), array('controller' => 'gallery_pictures', 'action' => 'delete', $attachment['GalleryPicture']['id'], 'gallery' => $gallery), null, __('Jesi siguran?', true));
			$actions .= ' ' . $layout->adminRowActions($attachment['GalleryPicture']['id']);

           
            $thumbnail = $html->image($attachment['GalleryPicture']['path_thumb']);
        
            $rows[] = array(
                       $attachment['GalleryPicture']['id'],
                       $thumbnail,
                       $attachment['GalleryPicture']['opis'],
					   $actions
                      );
        }

        echo $html->tableCells($rows);
        echo $tableHeaders;
    ?>
    </table>
</div>

<div class="paging"><?php echo $paginator->numbers(); ?></div>
<div class="counter"><?php echo $paginator->counter(array('format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true))); ?></div>
