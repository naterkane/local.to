<h2>Change Password</h2>
<form action="/change_password" method="post" accept-charset="utf-8">
	<div class="mod">
		<label for="password">Current Password</label>
		<?php echo $form->input('password', array('type'=>'password')) ?>
		<?php echo $form->error('old_password') ?>
	</div>	
	<div class="mod">
		<label for="password">New Password</label>
		<?php echo $form->input('new_password', array('type'=>'password')) ?>
		<?php echo $form->error('password') ?>
		<?php echo $form->error('password_confirm') ?>
	</div>
	<div class="mod">
		<label for="password">Password Confirm</label>
		<?php echo $form->input('new_password_confirm', array('type'=>'password')) ?>
		<?php echo $form->error('new_password_confirm') ?>		
	</div>
	<p><input type="submit" value="Update"></p>
</form>