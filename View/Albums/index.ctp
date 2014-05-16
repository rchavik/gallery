<h2><?php echo __d('gallery','Albums');?></h2>
<?php

if(count($albums) == 0):
	echo __d('gallery','No albums found.');
	return;
endif;

?>

<div class="albums">
<ul>
<?php foreach($albums as $album): ?>
	<li>
		<h3><?php echo $album['Album']['title']; ?></h3>
		<p>
		<?php
			if (empty($album['Photo'][0]['ThumbnailAsset'])):
				echo __d('gallery', 'This album has no photo');
				continue;
			endif;

			$path = $album['Photo'][0]['ThumbnailAsset']['path'];
			echo $this->Html->image(
				$this->Html->webroot($path, array(
					'style' => 'float:left;margin:5px 5px 5px 0px;',
				))
			);
			echo $album['Album']['description'];
		?>
		</p>
		<?php
			echo $this->Html->link(__d('gallery','View album'), array(
				'plugin' => 'gallery',
				'controller' => 'albums',
				'action' => 'view',
				'slug' => $album['Album']['slug']));
		?>
	</li>
<?php endforeach; ?>
</ul>
</div>

<div class="paging"><?php echo $this->Paginator->numbers(); ?></div>
