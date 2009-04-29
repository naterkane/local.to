<h2><?php echo $User['username'] ?></h2>

<form action="/delete" method="post" accept-charset="utf-8">
	<?php echo $form->input('update_key', array('type'=>'hidden')) ?>
	<input type="submit" id="delete" value="Delete this account" onclick="return confirm('Are you sure you want to delete your account? This cannot be undone.'); event.returnValue = false; return false;">
</form>
<form action="/settings" method="post" accept-charset="utf-8">
	<div class="mod">
		<label for="username">Real Name</label>
		<?php echo $form->input('realname') ?>
		<?php echo $form->error('realname') ?>
	</div>
	<div class="mod">
		<label for="username">User Name</label>
		<?php echo $form->input('username') ?>
		<?php echo $form->error('username') ?>
	</div>
	<div class="mod">
		<label for="username">Email</label>
		<?php echo $form->input('email') ?>
		<?php echo $form->error('email') ?>		
	</div>
	<div class="mod">
		<label for="username">Url</label>
		<?php echo $form->input('url') ?>
		<?php echo $form->error('url') ?>		
	</div>
	<div class="mod">
		<label for="username">Bio</label>
		<?php echo $form->input('bio') ?>
		<?php echo $form->error('bio') ?>		
	</div>	
	<div class="mod">
		<label for="username">Location</label>
		<?php echo $form->input('location') ?>
		<?php echo $form->error('location') ?>		
	</div>
	<div class="mod">
		<label for="timezone">Time Zone</label>	
		<?php echo $form->timezones('time_zone') ?>
		<?php echo $form->error('time_zone') ?>				
	</div>
	<div class="mod">
		<label for="locked">Lock Account</label>	
		<?php echo $form->checkbox('locked'); ?>
	</div>
	<div class="mod">
		<input type="submit" value="Update">
	</div>
</form>