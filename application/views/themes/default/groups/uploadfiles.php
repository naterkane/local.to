<div class="heading">
	<h2><?php echo $avatar->group($group, "48"); ?> <span><?php echo $html->groupName($group) ?></span></h2>
</div>
<div id="content">		
	<form id="upload_form" class="grid_9 alpha" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
	<fieldset>
		<legend>Share some files</legend>
		<?php echo $form->input('dropname',array('type'=>'hidden','value'=>$group['dropio'][0]['name'])); //var_dump($group['dropio'][0]['name']);?>
		
		<p>
			<label for="file_0">file</label>
			<?php echo $form->input('file_0', array('type'=>'file')) ?>
		</p>
		<p id="add_another_upload_link">
			<span class="note"><a href="#" onclick="javascript:addAnotherUpload();">Add another file</a></span>
		</p>
		<p class="submit">
			<input id="upload_button" class="button" type="submit" value="Upload"/>
		</p>
	</fieldset>
	</form>
</div>
<script>
var uploadfields = 1;
function addAnotherUpload(){
	$('#add_another_upload_link').before('<p id="uploadfield'+uploadfields+'"><label for="file_'+uploadfields+'">file</label><input type="file" name="file_'+uploadfields+'" id="file_'+uploadfields+'" value=""/><a href="#" onclick="javascript:deleteUploadField('+uploadfields+');"><small>delete</small></a></p>');
	uploadfields++;
}
function deleteUploadField(id) {
	$('#uploadfield'+id).remove();
}
$(document).ready(function(){
	$('#upload_form').submit(function(){
		$('#upload_button').addClass('disabled').text("Uploading");
		return true;
		});
});
</script>