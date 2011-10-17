<div class="users form">
    <h2><?php echo __d('gallery','Add album'); ?></h2>
    <?php echo $form->create('Album');?>
        <fieldset>
        <?php
            echo $form->input('title',array('label' => __('Title')));
            echo $form->input('slug');
			echo $form->input('description',array('label' => __('Description')));
			echo $form->input('type',array('label' => __('Type')));
			echo $form->input('params',array('label' => __('Parameters')));
			echo $form->input('status');
        ?>
        </fieldset>
    <?php echo $form->end('Submit');?>
</div>