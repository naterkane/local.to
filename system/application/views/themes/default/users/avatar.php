<div class="box">
<h2>Upload your avatar</h2>
	<ul class="nav">
		<li><?php echo $html->link('Profile Settings', '/settings') ?></li>
		<li><?php echo $html->link('Device settings', '/settings/sms') ?></li>
		<li><?php echo $html->link('Add/Edit Avatar', '/settings/avatar') ?></li>
		<li><?php echo $html->link('Change Password', '/change_password') ?></li>
		<li><?php echo $html->link('Delete Account', '/delete_account') ?></li>
	</ul>
	<div class="block">
	<form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
		<fieldset>
		<p>
			<?php echo $form->input('avatar', array('type'=>'file')) ?>
		</p>
		<p>
			<input class="confirm button" type="submit" value="Upload"/>
		</p>
		</fieldset>
	</form>
	<div class="clear"></div>
	</div>
</div>