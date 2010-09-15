<?php $x=false;?>
<?php if(!isset($album)): $x= true; $album = $this->requestAction(array('plugin' => 'gallery', 'controller' => 'albums', 'action' => 'view'), array('pass' => array('slug' => $slug))); endif; ?>
<?php if(!empty($album)): ?>

<?php if(isset($album['Photo']) && count($album['Photo'])): ?>
<div id="gallery-<?php echo $album['Album']['id']; ?>">
	<?php foreach($album['Photo'] as $photo): ?>
		<a href="<?php echo $this->Html->url('/img/photos/'. $photo['large']); ?>"><img src="<?php echo $this->Html->url('/img/photos/'. $photo['small']); ?>"></a>
	<?php endforeach; ?>
</div>

<script> $(function(){ $('#gallery-<?php echo $album['Album']['id']; ?>').galleria(); }); </script>
<?php else: ?>
	<?php  __d('gallery','No photos in the album'); ?>
<?php endif;?>
<?php else: ?>[Gallery:<?php echo $slug; ?>]<?php endif; ?>
