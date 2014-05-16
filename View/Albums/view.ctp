<h2><?php echo __d('gallery', 'Album');?>: <?php echo $album['Album']['title']; ?></h2>
<p><?php echo $album['Album']['description']; ?></p>

<?php echo $this->element('gallery_album'); ?>

<?php echo $this->Html->link(__d('gallery', 'View other albums'), array('action' => 'index')); ?>