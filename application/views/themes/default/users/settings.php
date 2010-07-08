<div class="heading">
	<h2>Settings</h2>
</div>
<div id="content">
	<?php $this->load->view('users/subnav/settings_nav');?>
	<form class="grid_10 alpha" action="/settings" method="post" accept-charset="utf-8">
		<fieldset>
			<legend>Edit Your Account &amp; Profile Information</legend>
			<p>
				<label for="realname">Full Name</label>
				<?php echo $form->input('realname') ?>
				<span class="note">Enter your real name, so people you know can recognize you</span>				
				<?php echo $form->error('realname') ?>
			</p>
			<p>
				<label for="username">Screenname</label>
				<?php echo $form->input('username') ?>
				<span class="note">A screenname may only contain letters (A-Za-z), numbers (0-9), and underscores (_). No spaces allowed.</span>
				<?php echo $form->error('username') ?>
			</p>
			<p>
				<label for="email">Email</label>
				<?php echo $form->input('email') ?>
				<?php echo $form->error('email') ?>		
			</p>
			<p>
				<label for="city">City</label>
				<?php echo $form->input('city') ?>
				<?php echo $form->error('city') ?>
			</p>
			<p>
				<label for="state">State</label>
				<?php echo $form->input('state') ?>
				<?php echo $form->error('state') ?>
			</p>
			<p>
				<label for="country">Country</label>
				<?php echo $form->input('country') ?>
				<?php echo $form->error('country') ?>
			</p>
			<p>
				<label for="postal_code">Postal Code</label>
				<?php echo $form->input('postal_code') ?>
				<?php echo $form->error('postal_code') ?>
			</p>				
			<p>
				<label for="gender">Gender</label>
				<?php echo $form->select('gender', array(''=>'Select One','Female'=>'Female', 'Male'=>'Male', 'Other'=>'Other'),array('no_blank'=>"value")) ?>
				<?php echo $form->error('gender') ?>
			</p>								
			<p>
				<label for="url">Url</label>
				<?php echo $form->input('url') ?>
				<?php echo $form->error('url') ?>		
			</p>
			<p>
				<label for="im">IM</label>
				<?php echo $form->input('im') ?>
				<?php echo $form->error('im') ?>		
			</p>
			<p>
				<label for="phone">Phone</label>
				<?php echo $form->input('phone') ?>
				<?php echo $form->error('phone') ?>		
			</p>
			<p>
				<label for="about_me">About Me</label>
				<?php echo $form->textarea('about_me') ?>
				<?php echo $form->error('about_me') ?>		
			</p>	
			
			<p>
				<label for="time_zone">Time Zone</label>	
				<?php echo $form->timezones('time_zone') ?>
				<?php echo $form->error('time_zone') ?>				
			</p>
					
			<p class="checkbox">
				<?php echo $form->checkbox('locked'); ?><label for="locked">Make my profile and posts private</label>
				<span class="note">Making your profile and posts private means only your approved followers can see your public updates.</span>	
			</p>
			<p class="checkbox">
				<?php echo $form->checkbox('threading'); ?><label for="threading">Display replies as threaded messages</label>	
				<span class="note">Threaded messages allow you to view replies to updates directly underneath the update.</span>
			</p>
		</fieldset>
		<?php /*?>
		<fieldset id="favorites">
			<legend>Favorites</legend>
			<p>
				<label for="favorite_sports">Favorite Sports</label>
				<?php echo $form->textarea('favorite_sports') ?>
				<?php echo $form->error('favorite_sports') ?>		
			</p>
			<p>
				<label for="favorite_teams">Favorite Teams</label>
				<?php echo $form->textarea('favorite_teams') ?>
				<?php echo $form->error('favorite_teams') ?>		
			</p>
			<p>
				<label for="favorite_players">Favorite Players</label>
				<?php echo $form->textarea('favorite_players') ?>
				<?php echo $form->error('favorite_players') ?>		
			</p>				
		</fieldset>
		<fieldset id="education">
			<legend>Education</legend>
			<p>
				<label for="college">College</label>
				<?php echo $form->input('college') ?>
				<?php echo $form->error('college') ?>		
			</p>
			<p>
				<label for="education">Education</label>
				<?php echo $form->input('education') ?>
				<?php echo $form->error('education') ?>		
			</p>
			<p>
				<label for="degree">Degree</label>
				<?php echo $form->input('degree') ?>
				<?php echo $form->error('degree') ?>		
			</p>
			<p>
				<label for="high_school">High School</label>
				<?php echo $form->input('high_school') ?>
				<?php echo $form->error('high_school') ?>		
			</p>
		</fieldset>
		<fieldset id="contact">
			<legend>Employment</legend>
			<p>
				<label for="employer">Current Employer</label>
				<?php echo $form->input('employer') ?>
				<?php echo $form->error('employer') ?>		
			</p>
			<p>
				<label for="position">Title</label>
				<?php echo $form->input('position') ?>
				<?php echo $form->error('position') ?>		
			</p>
			<p>
				<label for="employment_description">Description</label>
				<?php echo $form->textarea('employment_description') ?>
				<?php echo $form->error('employment_description') ?>	
			</p>
			<p>
				<label for="employment_location">Location</label>
				<?php echo $form->input('employment_location') ?>
				<?php echo $form->error('employment_location') ?>	
			</p>
			<p>
				<label for="employment_time_period">Time Period</label>
				<?php echo $form->input('employment_time_period') ?>
				<?php echo $form->error('employment_time_period') ?>	
			</p>								
		</fieldset>
		
    <?php */?>
		<p class="submit">
			<input type="submit" value="Save" class="button" />
			<a href="/home" class="secondary">Cancel</a>
		</p>			
	</form>
	<div class="clear"></div>
</div>