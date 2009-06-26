<div class="box">
<h2>Settings</h2>
	<ul class="nav">
		<li><?php echo $html->link('Profile Settings', '/settings') ?></li>
		<li><?php echo $html->link('Device settings', '/settings/sms') ?></li>
		<li><?php echo $html->link('Add/Edit Avatar', '/settings/avatar') ?></li>
		<li><?php echo $html->link('Change Password', '/change_password') ?></li>
		<li><?php echo $html->link('Delete Account', '/delete_account') ?></li>
	</ul>
	<div class="block">
	<p><?php echo $avatar->user($this->userData, "48" ); ?></p>
	<form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
		<fieldset>
			<legend>Upload a new avatar</legend>
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