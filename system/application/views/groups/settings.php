<h2><?php echo $name ?> (<a href="/group/<?php echo $name ?>">View</a>)</h2>
<form action="/groups/settings/<?php echo $name ?>" method="post" accept-charset="utf-8">
	<div class="mod">
		<label for="username">Name</label>
		<?php echo $this->form->input('name') ?>
		<?php echo $this->form->error('name') ?>
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
		<label for="username">Description</label>
		<?php echo $this->form->input('desc') ?>
		<?php echo $this->form->error('desc') ?>		
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