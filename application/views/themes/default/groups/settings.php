<div class="heading">
	<h2><?php echo $avatar->group($group, "48"); ?> <span><?php echo $html->groupName($group) ?></span></h2>
</div>
<div id="content">		
	<?php $this->load->view("groups/subnav/group_nav"); ?>	
	<form class="grid_9 alpha" action="/groups/settings/<?php echo $name ?>" method="post" accept-charset="utf-8">
		<fieldset>
			<legend>Edit Team Info<?php /*echo strtolower((substr($html->groupName($group),-1)) == "s")?$html->groupName($group)."'":$html->groupName($group)."'s"; */?></legend>
			<p class="checkbox"> 
				<?php echo $form->checkbox('locked'); ?><label for="locked">Make membership to this group private</label>
				<span class="note">Making this groups posts private means only members of this group can see updates posted to the group.</span>
				<?php echo $form->error('name') ?>	
			</p>
			<p>
				<label for="username">Screenanme</label>
				<?php echo $form->input('name') ?>
				<span class="note">A team's screenname may only contain letters (A-Za-z), numbers (0-9), and underscores (_). No spaces allowed.</span>
				<?php echo $form->error('name') ?>
			</p>
			<p>
				<label for="fullanme">Full Name</label>
				<?php echo $form->input('fullname') ?>
				<span class="note">Enter your real team name, so people can recognize the team. </span>
				<?php echo $form->error('fullname') ?>
			</p>				
			<p>
				<label for="email">Contact Email</label>
				<?php echo $form->input('email') ?>
				<?php echo $form->error('email') ?>		
			</p>
			<p>
				<label for="other_email">Additional Email</label>	
				<?php echo $form->input('other_email') ?>
				<?php echo $form->error('other_email') ?>				
			</p>	
			<p>
				<label for="url">Website</label>
				<?php echo $form->input('url') ?>
				<?php echo $form->error('url') ?>		
			</p>
			<p>
				<label for="desc">Profile</label>
				<?php echo $form->textarea('desc') ?>
				<?php echo $form->error('desc') ?>		
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
				<label for="sport">Sport</label>	
				<?php echo $form->input('sport') ?>
				<?php echo $form->error('sport') ?>				
			</p>
			<p>
				<label for="level">Level</label>	
				<?php echo $form->input('level') ?>
				<?php echo $form->error('level') ?>				
			</p>
			<p>
				<label for="gender">Gender</label>
				<?php echo $form->select('gender', array(''=>'Select One','Female'=>'Female', 'Male'=>'Male', 'Coed'=>'Coed')) ?>
				<?php echo $form->error('gender') ?>
			</p>
			<p>
				<label for="home_field">Home Field</label>	
				<?php echo $form->input('home_field') ?>
				<?php echo $form->error('home_field') ?>				
			</p>
			<p>
				<label for="league">League</label>	
				<?php echo $form->input('league') ?>
				<?php echo $form->error('league') ?>				
			</p>
			<p>
				<label for="division">Division</label>	
				<?php echo $form->input('division') ?>
				<?php echo $form->error('division') ?>				
			</p>
			<p>
				<label for="session_start">Season Start Date</label>	
				<?php echo $form->input('session_start') ?>
				<?php echo $form->error('session_start') ?>				
			</p>
			<p>
				<label for="session_end">Season End Date</label>	
				<?php echo $form->input('session_end') ?>
				<?php echo $form->error('session_end') ?>				
			</p>
			<p>
				<label for="recent_news">Recent News</label>	
				<?php echo $form->textarea('recent_news') ?>
				<?php echo $form->error('recent_news') ?>				
			</p>	
		</fieldset>
		<fieldset>
			<legend>Mailing Address</legend>			
			<p>
				<label for="address">Address</label>
				<?php echo $form->input('address') ?>				
				<?php echo $form->error('address') ?>
			</p>
			<p>
				<label for="address_line2">Address cont.</label>
				<?php echo $form->input('address_line2') ?>	
				<?php echo $form->error('address_line2') ?>					
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
			<p class="submit">
				<input type="submit" value="Update" class="button" />
				<a href="/group/<?php echo $name?>" class="secondary">Cancel</a>
			</p>
		</fieldset>
	</form>
	<div class="clear"></div>
</div>