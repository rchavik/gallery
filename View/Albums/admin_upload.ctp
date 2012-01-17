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
				<div style="float:left; padding:5px; position:relative; width: 48%; font-size: 0.8em; border-bottom: 1px solid #ddd;">
					<?php echo $this->Html->image('/'. $photo['small'], array(
							'style' => 'float: left',
							)); ?>
					<?php echo $this->Html->link(__d('gallery', 'remove', true), 'javascript:;', array(
					'rel' => $photo['id'],
					'class' => 'remove',
					'style' => 'position: absolute; top: 0px; right:40px; text-decoration: none;',
					));
					?>
					<?php echo $this->Html->link('edit', array(
						'controller' => 'photos',
						'action' => 'edit',
						$photo['id'],
						), array(
							'class' => 'edit',
							'style' => 'position: absolute; top: 0px; right:15px; text-decoration: none;',
							)
					);
					?>
					<?php if (!empty($photo['title'])): ?>
					<p style='float:left; clear: right;margin: 10px 0 0 10px; width: 69%;'>
					<strong>
					<?php echo $this->Text->truncate($photo['title'], 100, array('html' => true)); ?>
					</strong><br />
					<?php echo $this->Text->truncate($photo['description'], 120, array('html' => true)); ?></p>
					<?php endif; ?>
					</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<div class='pagelist' style='text-align: center;'>
	<?php echo $this->Html->link('prev ', '#', array(
	    'class' => 'gallery-prev',
		    )
	);
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
    var uploader = new qq.FileUploader({
        element: document.getElementById('upload'),
        action: '<?php echo $this->Html->url(array('action' => 'upload_photo', $album['Album']['id'])); ?>',
		onComplete: function(id, fileName, responseJSON){
			$('.qq-upload-fail').fadeOut(function(){
				$(this).remove();
			});
			$('#return').append('<div style="float:left; margin:5px; position:relative;"><a href="javascript:;" style="position:absolute; right:0px; top:0px; background:#FFF;" class="remove" rel="'+responseJSON.Photo.id+'"><?php __d('gallery','remove'); ?></a><a style="position:absolute; right:0px; bottom: 0px; background: #fff;" class="edit" href="/admin/gallery/photos/edit/'+responseJSON.Photo.id+'">edit</a><img src="/'+responseJSON.Photo.small+'" /></div>');
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

var wrap = $('#return');
$('.gallery-prev').click(function(){
	wrap.trigger('prev.evtpaginate');
	return false;
});

$('.gallery-next').click(function(){
	wrap.trigger('next.evtpaginate');
	return false;
});
wrap.bind('initialized.evtpaginate', function(e, startnum, totalnum ){
	$('#count').text(startnum);
	$('#total').text(totalnum);
});
	wrap.bind('finished.evtpaginate', function(e, num, isFirst, isLast ){
	$('#count').text(num);
});

// call the plugin
wrap.evtpaginate({
	perPage:12
	});
});

</script>
