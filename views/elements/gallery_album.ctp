<?php $x=false;?>
<?php if(!isset($album)): $x= true; $album = $this->requestAction(array('plugin' => 'gallery', 'controller' => 'albums', 'action' => 'view'), array('pass' => array('slug' => $slug))); endif; ?>
<?php if(!empty($album)): ?>

<?php if(isset($album['Photo']) && count($album['Photo'])): ?>
<?php
$out = '';
foreach($album['Photo'] as $photo) {
	$out .= $this->Gallery->photo($album, $photo);
}
echo $this->Gallery->album($album, $out);
$this->Gallery->initialize($album);
?>

<?php else: ?>
	<?php  __d('gallery','No photos in the album'); ?>
<?php endif;?>
<?php else: ?>[Gallery:<?php echo $slug; ?>]<?php endif; ?>
