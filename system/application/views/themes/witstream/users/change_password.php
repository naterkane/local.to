<div class="box">
	<h2>Settings</h2>
	<div class="block">
		<form action="/change_password" method="post" accept-charset="utf-8">
			<fieldset class="login">
				<legend>Change Password</legend>
				<p>
					<label for="password">Current Password</label>
					<?php echo $form->input('password', array('type'=>'password')) ?>
					<?php echo $form->error('old_password') ?>
				</p>	
				<p>
					<label for="password">New Password</label>
					<?php echo $form->input('new_password', array('type'=>'password')) ?>
					<?php echo $form->error('password') ?>
					<?php echo $form->error('password_confirm') ?>
				</p>
				<p>
					<label for="password">Password Confirm</label>
					<?php echo $form->input('new_password_confirm', array('type'=>'password')) ?>
					<?php echo $form->error('new_password_confirm') ?>		
				</p>
				<p><input class="confirm button" type="submit" value="Update"></p>
			</fieldset>
		</form>
	</div>
</div>