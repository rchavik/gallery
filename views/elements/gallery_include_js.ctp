<?php

$albumType = empty($album['Album']['type']) ? 'gallery' : $album['Album']['type'];

switch ($albumType) {

case 'nivo-slider':
	echo $html->script('/gallery/js/jquery.nivo.slider', false);
	echo $html->css('/gallery/css/nivo-slider', false);
	echo $html->css('/gallery/css/nivo-style', false);
	break;

case 'gallery':
default:
	echo $html->script('/gallery/js/galleria', false);
?>
<script> Galleria.loadTheme('<?php echo $this->Html->url('/gallery/js/themes/classic/galleria.classic.js'); ?>');</script>
<?php
	break;
};