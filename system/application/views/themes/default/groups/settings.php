<div class="box">
	<h2>Settings</h2>
	<div class="block">
		<form class="grid_9 alpha" action="/groups/settings/<?php echo $name ?>" method="post" accept-charset="utf-8">
			<fieldset>
				<legend>Edit <?php echo (substr($name,-1) == "s")?$name."'":$name."'s"; ?> Profile</legend>
				<p>
					<label for="username">Name</label>
					<?php echo $form->input('name') ?>
					<?php echo $form->error('name') ?>
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
					<label for="username">Description</label>
					<?php echo $form->input('desc') ?>
					<?php echo $form->error('desc') ?>		
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
					<input type="submit" value="Update" class="button" />
					<a href="/groups/<?php echo $name?>" class="toggler">Cancel</a>
				</p>
			</fieldset>
		</form>
		<div class="clear"></div>
	</div>
</div>