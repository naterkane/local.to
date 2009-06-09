<div class="settings">
	<div class="top short">
		<h3>Upload your Photo!</h2>
	</div>
	<div class="box content">
	<div class="avatar-original"><?php echo $avatar->user($this->userData, "original" ); ?></div>
	<form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
		<fieldset>
	<p>
		<?php echo $form->input('avatar', array('type'=>'file')) ?>
	</p>
	<p>
		<input class="confirm button" type="submit" value="Upload" /><a href="/settings" class="toggler">Cancel</a>
	</p>
	</fieldset>
	</form>
	</div>
</div>