<?php $x=false;?>
<?php if(!isset($album)): $x= true; $album = $this->requestAction(array('plugin' => 'gallery', 'controller' => 'albums', 'action' => 'view'), array('pass' => array('slug' => $slug))); endif; ?>
<?php if(!empty($album)): ?>

<?php if(isset($album['Photo']) && count($album['Photo'])): ?>

<?php
	$albumId = 'gallery-' . $album['Album']['id'];
	$albumType = $album['Album']['type'];
?>
<div id="<?php echo $albumId; ?>">
<?php
foreach($album['Photo'] as $photo) {
	$options = array();
	$urlLarge = $this->Html->url('/img/photos/' . $photo['large']);
	$urlSmall = $this->Html->url('/img/photos/' . $photo['small']);
	$config = $this->Gallery->getAlbumJsParams($album);

	switch ($albumType) {
	case 'nivo-slider':
		$title = empty($photo['title']) ? false : $photo['title'];
		if ($title) {
			$options = Set::merge(array('title' => $title), $options);
		}
		echo $this->Html->image($urlLarge, $options);
		break; 

	case 'gallery':
	default:
		$imgTag = $this->Html->image($urlSmall);
		echo $this->Html->tag('a', $imgTag, array('href' => $urlLarge));
		break;
	}
}
?>
</div>

<?php

	switch ($albumType) {
	case 'nivo-slider':
?>
<script> $(function(){ $('#' + '<?php echo $albumId; ?>').nivoSlider(<?php echo $config; ?>); }); </script>
<?php
		break;
	case 'gallery':
	default:
?>
<script> $(function(){ $('#' + '<?php echo $albumId; ?>').galleria(<?php echo $config; ?>); }); </script>
<?php
		break;
}
?>

<?php else: ?>
	<?php  __d('gallery','No photos in the album'); ?>
<?php endif;?>
<?php else: ?>[Gallery:<?php echo $slug; ?>]<?php endif; ?>
