<?php

$this->extend('/Common/admin_edit');

$editUrl = $this->Html->link($album['Album']['title'], array(
	'plugin' => 'gallery',
	'controller' => 'albums',
	'action' => 'edit',
	$album['Album']['id'],
));

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb('Gallery')
	->addCrumb(__d('gallery', 'Albums'), array(
		'admin' => true,
		'plugin' => 'gallery',
		'controller' => 'albums',
		'action' => 'index'
	));

if (empty($album)):
	$this->Html->addCrumb(__d('gallery', 'Add'), $this->here);
else:
	$this->Html
		->addCrumb($album['Album']['title'], array(
			'action' => 'edit', $album['Album']['id'],
		))
		->addCrumb(__d('gallery', 'Photos'), $this->here);
endif;

$this->start('actions');
	echo $this->Form->postLink(__('Reset weight'),
		array(
			'plugin' => 'gallery',
			'controller' => 'albums',
			'action' => 'reset_weight',
			$album['Album']['id'],
		),
		array(
			'button' => 'default',
			'escape' => false,
		),
		__('You will lose existing order for this album. Continue?')
	);
$this->end();

?>
<div class="row-fluid">

	<div id="upload" class="span12">
	</div>
	<div id="return" class="span12">

		<?php if(isset($album['Photo'])): ?>

			<?php foreach($album['Photo'] as $photo): ?>
				<div class="album-photo clearfix">
					<div class="span3">
					<?php
					echo $this->Html->link(
						$this->Html->image($photo['ThumbnailAsset']['path'], array('class' => 'img-polaroid')),
						$photo['LargeAsset']['path'],
						array(
							'rel' => 'gallery-' . $photo['AlbumsPhoto']['album_id'],
							'class' => 'thickbox',
							'escape' => false,
						)
					);
					?>
					</div>

					<div class="path span6">
					<?php
						$filename = basename($photo['LargeAsset']['path']);
						$filename = $this->Html->link(
							$this->Text->truncate($filename, 120),
							$photo['OriginalAsset']['path'],
							array('target' => '_blank', 'title' => $filename)
						);
						echo __('Filename: %s', $filename);
					?>
					</div>

					<?php if (!empty($photo['title'])): ?>
					<div class="description">
						<?php echo $this->Html->tag('strong', $this->Text->truncate(strip_tags($photo['title']), 100)); ?>
						<br />
						<?php echo $this->Text->truncate(strip_tags($photo['description']), 120); ?></div>
					<?php endif; ?>

					<?php echo $this->element('Gallery.admin/album_actions', array('photo' => $photo)); ?>
				</div>
				<?php endforeach; ?>
			<?php endif; ?>
	</div>

	<div class='pagelist' style='text-align: center;'>
	<?php echo $this->Html->link('prev ', '#', array(
		'class' => 'gallery-prev',
	));
	?>
	<span id='count'></span>
	|
	<span id='total'></span>
	<?php echo $this->Html->link(' next', '#', array(
		'class' => 'gallery-next',
		)
	);
	?>
	</div>

</div>
<?php echo $this->Html->script('/gallery/js/fileuploader', false);  echo $this->Html->css('/gallery/css/fileuploader', false); ?>
<script>
function createUploader(){
	var containerTemplate = _.template(
		'<div class="album-photo clearfix">' +
		'	<div class="span3">' +
		'		<a class="thickbox" rel="gallery-<%= Photo.album_id %>"' +
		'			href="<%= LargeAsset.path %>">' +
		'			<img src="<%= ThumbnailAsset.path %>" class="img-polaroid">' +
		'		</a>' +
		'	</div>' +

		'	<div class="path span6">' +
		'		Filename: ' +
		'		<a target="_blank" title="<%= OriginalAsset.path %>"' +
		'			href="<%= OriginalAsset.path %>">' +
		'			<%= OriginalAsset.path.slice(OriginalAsset.path.lastIndexOf("/") + 1) %>' +
		'		</a>' +
		'	</div>' +

		'	<div class="photo-actions span3">' +
		'		<a class="remove" href="javascript:void(0);" rel="<%= Photo.id %>"><i class="icon-trash icon-small"></i> <%= sRemove %></a>' +
		'		<a class="edit" href="/admin/gallery/photos/edit/<%= Photo.id %>"><i class="icon-edit icon-small"></i> <%= sEdit %></a>' +
		'		<a class="up" href="/admin/gallery/photos/moveup/<%= Photo.id %>"><i class="icon-chevron-up icon-small"></i> <%= sUp %></a>' +
		'		<a class="down" href="/admin/gallery/photos/movedown/<%= Photo.id %>"><i class="icon-chevron-down icon-small"></i> <%= sDown %></a>' +
		'	</div>' +
		'</div>'
	);

	var uploader = new qq.FileUploader({
		element: document.getElementById('upload'),
		action: '<?php echo $this->Html->url(array('action' => 'upload_photo', $album['Album']['id'])); ?>',
		onComplete: function(id, fileName, json) {
			$('.qq-upload-fail').fadeOut('fast', function(){
				$(this).remove();
			});
			var sRemove = '<?php echo __d('gallery', 'remove'); ?>';
			var sEdit = '<?php echo __d('gallery', 'edit'); ?>';
			var sUp = '<?php echo __d('gallery', 'up'); ?>';
			var sDown = '<?php echo __d('gallery', 'down'); ?>';
			var args = {
				Photo: json.Photo,
				OriginalAsset: json.OriginalAsset,
				ThumbnailAsset: json.ThumbnailAsset,
				LargeAsset: json.LargeAsset,
				sRemove: sRemove,
				sEdit: sEdit,
				sUp: sUp,
				sDown: sDown,
			};
			$('#return').append(containerTemplate(args));
			tb_init('a.thickbox');
		}
	});
}

// in your app create uploader as soon as the DOM is ready
// don't wait for the window to load
$(function(){
	createUploader();
	$('.remove').live('click', function(){
		var obj = $(this);
		var url = '<?php echo $this->Html->url('/admin/gallery/albums/delete_photo/');?>'+obj.attr('rel');
		$.ajax(url, {
			type: 'POST',
			success: function(data, textStatus, jqXHR) {
				var json = $.parseJSON(data);
				if (json['status'] == 1) {
					obj.parents('.album-photo').fadeOut('fast', function() {
						$(this).remove();
					});
				} else {
					alert(json['msg']);
				}
			}
		});
	});

});

</script>
