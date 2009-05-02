<div class="box">
	<h2>Settings</h2>
	<h3><?php echo $User['username'] ?>'s settings</h3>
	<div class="block">
		<form class="grid_9 alpha" action="/settings" method="post" accept-charset="utf-8">
			<fieldset>
				<legend>Account Information</legend>
				<p>
					<label for="username">Real Name</label>
					<?php echo $form->input('realname') ?>
					<?php echo $form->error('realname') ?>
				</p>
				<p>
					<label for="username">User Name</label>
					<?php echo $form->input('username') ?>
					<?php echo $form->error('username') ?>
				</p>
				<p>
					<label for="username">Email</label>
					<?php echo $form->input('email') ?>
					<?php echo $form->error('email') ?>		
				</p>
				<p>
					<label for="username">Url</label>
					<?php echo $form->input('url') ?>
					<?php echo $form->error('url') ?>		
				</p>
				<p>
					<label for="username">Bio</label>
					<?php echo $form->input('bio') ?>
					<?php echo $form->error('bio') ?>		
				</p>	
				<p>
					<label for="username">Location</label>
					<?php echo $form->input('location') ?>
					<?php echo $form->error('location') ?>		
				</p>
				<p>
					<label for="timezone">Time Zone</label>	
					<?php echo $form->timezones('time_zone') ?>
					<?php echo $form->error('time_zone') ?>				
				</p>
				<p>
					<label for="locked">Lock Account</label>	
					<?php echo $form->checkbox('locked'); ?>
				</p>
				<p>
					<input type="submit" value="Update"/>
				</p>
			</fieldset>
		</form>
		<div class="clear"></div>
		<form class="grid_9 alpha" action="/change_password" method="post" accept-charset="utf-8">
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
		<div class="clear"></div>
	</div>
</div>
<div class="box">
	<div class="block">
		<form class="grid_9 alpha" action="/delete" method="post" accept-charset="utf-8">
			<fieldset>
				<legend>Delete this account</legend>
			<?php echo $form->input('update_key', array('type'=>'hidden')) ?>
			<input type="submit" id="delete" value="yeah, i'm serious, just do it." onclick="return confirm('Are you sure you want to delete your account? This cannot be undone.'); event.returnValue = false; return false;">
			</fieldset>
		</form>
		<div class="clear"></div>
	</div>
</div>