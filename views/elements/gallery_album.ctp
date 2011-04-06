<?php $x=false;?>
<?php if(!isset($album)): $x= true; $album = $this->requestAction(array('plugin' => 'gallery', 'controller' => 'albums', 'action' => 'view'), array('pass' => array('slug' => $slug))); endif; ?>
<?php if(!empty($album)): ?>

<?php if(isset($album['Photo']) && count($album['Photo'])): ?>

<?php
	$albumId = 'gallery-' . $album['Album']['id'];
	$albumType = $album['Album']['type'];
?>
<div id="<?php echo $albumId; ?>">
<?php foreach($album['Photo'] as $photo): ?>
<?php
	$urlLarge = $this->Html->url('/img/photos/' . $photo['large']);
	$urlSmall = $this->Html->url('/img/photos/' . $photo['small']);
	switch ($albumType) {
	case 'nivo-slider':
?>
		<img src="<?php echo $urlLarge; ?>">
<?php
		break; 
	case 'gallery':
	default:
	?>
		<a href="<?php echo $urlLarge; ?>"><img src="<?php echo $urlSmall; ?>"></a>
	<?php	break;
	}
	?>
<?php endforeach; ?>
</div>

<?php

	switch ($albumType) {
	case 'nivo-slider':
?>
<script> $(function(){ $('#' + '<?php echo $albumId; ?>').nivoSlider(); }); </script>
<?php
		break;
	case 'gallery':
	default:
?>
<script> $(function(){ $('#' + '<?php echo $albumId; ?>').galleria(); }); </script>
<?php
		break;
}
?>

<?php else: ?>
	<?php  __d('gallery','No photos in the album'); ?>
<?php endif;?>
<?php else: ?>[Gallery:<?php echo $slug; ?>]<?php endif; ?>
