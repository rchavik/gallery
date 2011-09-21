<?php
echo $this->Form->create('Optimize', array('id' => 'optimize_img'));
echo $this->Form->input('path', array(
		'id' => 'imgpath'
		));
echo $this->Form->input('jpgquality', array(
		'id' => 'jpgquality'
		));
echo $this->Form->input('pngquality', array(
		'id' => 'pngquality'
		));
echo $this->Form->end('submit');
?>
<div id='status'>
</div>
<script>
$('#optimize_img').submit(function() {
	var imgPath = $('#imgpath').attr('value');
	var typeJpg = $('#jpgquality').attr('value');
	var typePng = $('#pngquality').attr('value');
	var data = $('#optimize_img').serialize();

	$.ajax({
		type: 'POST',
		url: Croogo.basePath + 'gallery/optimize/getdata',
		data: data,
		success: function() {
			$('#status').prepend(
				'<p style="display: none;">' + 
					'Image Path: ' + imgPath + '<br />' +
				'</p>'
				);
			$('#status p:first').fadeIn();
		}
	});
	return false;
});
</script>
