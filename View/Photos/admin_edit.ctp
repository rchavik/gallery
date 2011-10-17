<div class="links form">
    <h2><?php echo $title_for_layout; ?></h2>
    <?php echo $form->create('GalleryPicture', array('url' => array('controller' => 'gallery_pictures', 'action' => 'edit', 'gallery' => $gallery)));?>
        <fieldset>
                    <?php
			 		   
					   echo $form->input('id');
                       echo $form->input('gallery_id', array(
                            'label' => __('Gallery'),
                            'options' => $galerije,
                            'empty' => false,
                        ));                     
                        echo $form->input('opis', array('label' => __('Description')));
                    ?>
            
        </fieldset>
    <?php echo $form->end('Submit');?>
</div>