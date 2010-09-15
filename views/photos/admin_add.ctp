<div style="position:relative; width:100%;">
    <h2><?php echo $title_for_layout; ?></h2>
	<?php echo $this->Html->script('/gallery/js/swfobject'); ?>
	<script type="text/javascript">
		var flashvars = {};
		flashvars.path = "<?php echo $this->Html->url('/admin/gallery/gallery_pictures/upload/'.$gallery);?>";
		var params = {};
		params.menu = "false";
		params.scale = "noscale";
		params.salign = "tm";
		var attributes = {};
		attributes.align = "middle";
		
		swfobject.embedSWF("<?php echo $this->Html->url('/gallery/swf/imgUpload.swf'); ?>", "flashHolder", "100%", "100%", "10.0.0", false, flashvars, params, attributes);
	</script>
	
	<div id='mainHolder' style='position:absolute;width:100%;height:300px;'>
		<div id="flashHolder">
			<a href="http://www.adobe.com/go/getflashplayer">
				<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
			</a>
		</div>
	</div>
</div>