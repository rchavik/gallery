<?php $x=false;?>
<?php if(!isset($album)): $x= true; $album = $this->requestAction(array('plugin' => 'gallery', 'controller' => 'albums', 'action' => 'view'), array('pass' => array('slug' => $slug))); endif; ?>
<?php if(!empty($album)): ?>

<?php if(isset($album['Photo']) && count($album['Photo'])): ?>

<?php
$albumId = 'gallery-' . $album['Album']['id'];
$albumType = $album['Album']['type'];
$out = '';
foreach($album['Photo'] as $photo) {
	$options = array();
	$urlLarge = $this->Html->url('/img/photos/' . $photo['large']);
	$urlSmall = $this->Html->url('/img/photos/' . $photo['small']);
	$config = $this->Gallery->getAlbumJsParams($album);

	switch ($albumType) {
	case 'nivo-slider':
		$title = empty($photo['title']) ? false : $photo['title'];
		$options = Set::merge(array('rel' => $urlSmall), $options);
		if ($title) {
			$options = Set::merge(array('title' => $title), $options);
		}
		$out .= $this->Html->image($urlLarge, $options);
		break; 
		
	case 'DDSlider':
		$title = empty($photo['title']) ? false : $photo['title'];
		$options = Set::merge(array('rel' => $urlSmall), $options);
		if ($title) {
			$options = Set::merge(array('title' => $title), $options);
		}
		$out .= $this->Html->tag('li', $this->Html->image($urlLarge, $options));
		break; 
	
	case 'pikachoose':
		$title = empty($photo['title']) ? false : $photo['title'];
		$options = Set::merge(array('rel' => $urlSmall), $options);
		if ($title) {
			$options = Set::merge(array('title' => $title), $options);
		}
		$out .= $this->Html->tag('li', $this->Html->image($urlLarge, $options));
		break; 
		
	case 'gallery':
	default:
		$imgTag = $this->Html->image($urlSmall);
		$out .= $this->Html->tag('a', $imgTag, array('href' => $urlLarge));
		break;
	}
}

switch ($albumType) {

case 'DDSlider':
	echo $this->Html->tag('ul', $out, array('id' => $albumId));
	break;

case 'pikachoose':
	echo $this->Html->tag('ul', $out, array('id' => $albumId));
	break;
	
default:
	echo $this->Html->tag('div', $out, array('id' => $albumId));
	break;
	
}

switch ($albumType) {
case 'nivo-slider':
	$initializer = 'nivoSlider'; break;

case 'DDSlider':
	$initializer = 'DDSlider'; break;
	
case 'pikachoose':
	$initializer = 'PikaChoose'; break;
	
case 'gallery':
default:
	$initializer = 'galleria'; break;
}

$js = sprintf('$(function(){ $(\'#%s\').%s(%s); })',
	$albumId,
	$initializer,
	$config
	);

echo $this->Html->scriptBlock($js);
?>

<?php else: ?>
	<?php  __d('gallery','No photos in the album'); ?>
<?php endif;?>
<?php else: ?>[Gallery:<?php echo $slug; ?>]<?php endif; ?>
