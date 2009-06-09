<div class="settings">
	<div class="top short">
	<h3>Settings</h3>
	</div>
	<div class="box">
		<form class="" action="/settings" method="post" accept-charset="utf-8">
			<fieldset>
				<legend>Edit Your Account Information</legend>
				<p>
					<label for="realname">Real Name</label>
					<?php echo $form->input('realname') ?>
					<?php echo $form->error('realname') ?>
				</p>
				<p>
					<label for="username">User Name</label>
					<?php echo $form->input('username') ?>
					<?php echo $form->error('username') ?>
				</p>
				<p>
					<label for="email">Email</label>
					<?php echo $form->input('email') ?>
					<?php echo $form->error('email') ?>		
				</p>
				<p>
					<label for="url">Url</label>
					<?php echo $form->input('url') ?>
					<?php echo $form->error('url') ?>		
				</p>
				<p>
					<label for="bio">Bio</label>
					<?php echo $form->input('bio') ?>
					<?php echo $form->error('bio') ?>		
				</p>	
				<p>
					<label for="location">Location</label>
					<?php echo $form->input('location') ?>
					<?php echo $form->error('location') ?>		
				</p>
				<p>
					<label for="time_zone">Time Zone</label>	
					<?php echo $form->timezones('time_zone') ?>
					<?php echo $form->error('time_zone') ?>				
				</p>
				<p>
					<label for="carrier">Carriers</label>	
					<?php echo $form->select('carrier', $carriers) ?>
					<?php echo $form->error('carrier') ?>		
				</p>
				<p>
					<label for="device_updates">Device Updates</label>	
					<?php echo $form->checkbox('device_updates'); ?>
				</p>
				<p>
					<label for="phone">Phone</label>	
					<?php echo $form->input('phone'); ?>
				</p>								
				<p>
					<label for="locked">Lock Account</label>	
					<?php echo $form->checkbox('locked'); ?>
				</p>
				<p>
					<label for="threading">Enable Threading</label>	
					<?php echo $form->checkbox('threading'); ?>
				</p>
				<p>
					<input type="submit" value="Update" class="button" />
					<a href="/home" class="toggler">Cancel</a>
				</p>
			</fieldset>
			<hr/>
			<fieldset>	
				<legend>Picture</legend>
				<p>
					<?php echo $avatar->user($this->userData, "50" ); ?> 
					<a href="/settings/avatar" class="button">Change your picture</a></p>
				</p>
			</fieldset>
		</form>
		<hr/>
		<form class="" action="/change_password" method="post" accept-charset="utf-8">
			<fieldset class="login">
				<legend>Change Password</legend>
				<p>
					<label for="password">Current Password</label>
					<?php echo $form->input('password', array('type'=>'password')) ?>
					<?php echo $form->error('old_password') ?>
				</p>	
				<p>
					<label for="new_password">New Password</label>
					<?php echo $form->input('new_password', array('type'=>'password')) ?>
					<?php echo $form->error('password') ?>
					<?php echo $form->error('password_confirm') ?>
				</p>
				<p>
					<label for="new_password_confirm">Password Confirm</label>
					<?php echo $form->input('new_password_confirm', array('type'=>'password')) ?>
					<?php echo $form->error('new_password_confirm') ?>		
				</p>
				<p><input class="confirm button" type="submit" value="Update" /></p>
			</fieldset>
		</form>
		<hr/>
		<form class="" action="/delete" method="post" accept-charset="utf-8">
			<fieldset>
				<legend>Delete this account</legend>
				<?php echo $form->input('update_key', array('type'=>'hidden')) ?>
				<input type="submit" id="delete" value="yeah, i'm serious, just do it." onclick="return confirm('Are you sure you want to delete your account? This can not be undone.'); event.returnValue = false; return false;" />
			</fieldset>
		</form>
	</div>
</div>