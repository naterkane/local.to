<div class="box">
<h2>Delete your Account</h2>
	<ul class="nav">
		<li><?php echo $html->link('Profile Settings', '/settings') ?></li>
		<li><?php echo $html->link('Device settings', '/settings/sms') ?></li>
		<li><?php echo $html->link('Add/Edit Avatar', '/settings/avatar') ?></li>
		<li><?php echo $html->link('Change Password', '/change_password') ?></li>
		<li><?php echo $html->link('Delete Account', '/delete_account') ?></li>
	</ul>
	<div class="block">
		<form class="grid_9 alpha" action="/delete" method="post" accept-charset="utf-8">
			<fieldset>
				<legend>Delete this account</legend>
			<?php echo $form->input('update_key', array('type'=>'hidden')) ?>
			<input type="submit" id="delete" value="yeah, i'm serious, just do it." onclick="return confirm('Are you sure you want to delete your account? This cannot be undone.'); event.returnValue = false; return false;" />
			</fieldset>
		</form>
		<div class="clear"></div>
	</div>
</div>