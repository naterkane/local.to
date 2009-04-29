<h2><?php echo $name ?> (<a href="/group/<?php echo $name ?>">View</a>)</h2>
<form action="/groups/settings/<?php echo $name ?>" method="post" accept-charset="utf-8">
	<div class="mod">
		<label for="username">Name</label>
		<?php echo $form->input('name') ?>
		<?php echo $form->error('name') ?>
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
		<label for="username">Description</label>
		<?php echo $form->input('desc') ?>
		<?php echo $form->error('desc') ?>		
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
		<input type="submit" value="Update">
	</div>
</form>