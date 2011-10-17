<div class="users index">
    <h2><?php echo $title_for_layout; ?></h2>

	<?php
	$editUrl = $this->Html->link($album['Album']['title'], array(
		'plugin' => 'gallery',
		'controller' => 'albums',
		'action' => 'edit',
		$album['Album']['id'],
		)
	);
	?>

	<h3><?php echo sprintf(__d('gallery', 'Album: %s'), $editUrl); ?></h3>

    <div id="upload">

    </div>
	<br clear="both" />
	<div id="return" class="clearfix">
		<?php if(isset($album['Photo'])): ?>
			<?php foreach($album['Photo'] as $photo): ?>
				<div style="float:left; margin:5px; position:relative;"><a href="javascript:;" style="position:absolute; right:0px; top:0px; background:#FFF;" class="remove" rel="<?php echo $photo['id']; ?>"><?php echo __d('gallery','remove'); ?></a><?php echo $this->Html->image('/'. $photo['small']); ?></div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
<?php echo $this->Html->script('/gallery/js/fileuploader', false);  echo $this->Html->css('/gallery/css/fileuploader', false); ?>
<script>
function createUploader(){            
    var uploader = new qq.FileUploader({
        element: document.getElementById('upload'),
        action: '<?php echo $this->Html->url(array('action' => 'upload_photo', $album['Album']['id'])); ?>',
		onComplete: function(id, fileName, responseJSON){
			$('.qq-upload-fail').fadeOut(function(){
				$(this).remove();
			});
			$('#return').append('<div style="float:left; margin:5px; position:relative;"><a href="javascript:;" style="position:absolute; right:0px; top:0px; background:#FFF;" class="remove" rel="'+responseJSON.Photo.id+'"><?php echo __d('gallery','remove'); ?></a><img src="/'+responseJSON.Photo.small+'" /></div>');
		},
		
	        template: '<div class="qq-uploader">' + 
	                '<div class="qq-upload-drop-area"><span><?php echo __d('gallery','Drop files here to upload'); ?></span></div>' +
					'<a class="qq-upload-button ui-corner-all" style="background-color:#EEEEEE;float:left;font-weight:bold;margin-right:10px;padding:10px;text-decoration:none;cursor:pointer;"><?php echo __d('gallery','Add new photos'); ?></a>' +
					'<ul class="qq-upload-list"></ul>' + 
	             '</div>',
		      
			fileTemplate: '<li>' +
		                '<span class="qq-upload-file"></span>' +
		                '<span class="qq-upload-spinner"></span>' +
		                '<span class="qq-upload-size"></span>' +
		                '<a class="qq-upload-cancel" href="#"><?php echo __d('gallery','cancel'); ?></a>' +
		            '</li>',

    });           
}

// in your app create uploader as soon as the DOM is ready
// don't wait for the window to load  
$(function(){
	createUploader();
	$('.remove').live('click', function(){
		var obj = $(this);
		$.getJSON('<?php echo $this->Html->url('/admin/gallery/albums/delete_photo/');?>'+obj.attr('rel'), function(r) {
            if (r['status'] == 1) {
				obj.parent().fadeOut(function(){
					$(this).remove();
				});
            } else {
                alert(r['msg']);	
            }
		});
	});
});

</script>