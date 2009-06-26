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
		<form class="grid_9 alpha" action="/settings" method="post" accept-charset="utf-8">
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
		</form>
		<div class="clear"></div>
	</div>
</div>