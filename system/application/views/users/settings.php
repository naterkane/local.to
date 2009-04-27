<h2><?php echo $User['username'] ?></h2>

<p><a href="/delete" id="delete" onclick="return confirm('Are you sure you want to delete your account? This cannot be undone.'); event.returnValue = false; return false;">Delete this account</a></p>

<form action="/settings" method="post" accept-charset="utf-8">
	<div class="mod">
		<label for="username">Real Name</label>
		<?php echo $this->form->input('realname') ?>
		<?php echo $this->form->error('realname') ?>
	</div>
	<div class="mod">
		<label for="username">User Name</label>
		<?php echo $this->form->input('username') ?>
		<?php echo $this->form->error('username') ?>
	</div>
	<div class="mod">
		<label for="username">Email</label>
		<?php echo $this->form->input('email') ?>
		<?php echo $this->form->error('email') ?>		
	</div>
	<div class="mod">
		<label for="username">Url</label>
		<?php echo $this->form->input('url') ?>
		<?php echo $this->form->error('url') ?>		
	</div>
	<div class="mod">
		<label for="username">Bio</label>
		<?php echo $this->form->input('bio') ?>
		<?php echo $this->form->error('bio') ?>		
	</div>	
	<div class="mod">
		<label for="username">Location</label>
		<?php echo $this->form->input('location') ?>
		<?php echo $this->form->error('location') ?>		
	</div>
	<div class="mod">
		<label for="timezone">Time Zone</label>	
		<?php echo $this->form->select('timezone', $this->User->timezones) ?>
	</div>
	<div class="mod">
		<input type="submit" value="Update">
	</div>
</form>