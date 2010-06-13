<div class="heading">
	<h2>
		<?php echo $avatar->user($user, "48"); ?> 
		<span<?php 
					if ($user['locked'] == true){ echo ' class="private"';}
					?>><?php echo $html->name($user); ?></span>
	</h2>
</div>
<div id="content">
	<?php 
	if ($isLocked == true) 
	{
		?>
		<div class="inlineMessage"><p><strong>This user has protected their profile.</strong></p></div>
		<?php
	}
	else { ?>
	<div class="content vcard">
		<h3>Contact Information</h3>
			<dl>
				<dt>Name</dt>
				<dd><span class="fn n"><?php echo $user['realname']; ?></span> - @<span class="nickname"><?php echo $user['username']; ?></span></dd>
		
				<dt>Email</dt>
					<dd class="email"><?php echo $user['email']; ?></dd>
				
				<dt>Location</dt>
				<dd class="adr"><?php 
					if (!empty($user['address']) || !empty($user['address_line2'])){?><span class="street-address"><?php }
					if (!empty($user['address'])) { echo $user['address']; ?><br /><?php } 
					if (!empty($user['address_line2'])) { echo $user['address_line2']; ?><br /><?php } 
					if (!empty($user['address']) || !empty($user['address_line2'])){?></span><?php }
					if (!empty($user['city'])) { echo '<span class="locality">'. $user['city'] .'</span>';  }
					if (!empty($user['city']) && ($user['state'] || $user['postal_code'])) {
						?>, <?php } 
					if (!empty($user['state'])) { echo '<span class="region">'. $user['state'] .'</span>'; }  
					if (!empty($user['postal_code'])) { echo '<span class="postal-code">'. $user['postal_code'] .'</span>'; } ?> <br/> <?php
					if (!empty($user['country'])) { echo '<span class="country">'. $user['country'] .'</span>'; } ?></dd>	
			<?php if (!empty($user['gender'])) { ?>	
				<dt>Gender</dt>
				<dd><?php echo $user['gender']; ?></dd>		
			<?php } ?>	
			<?php if (!empty($user['url'])) { 
			   $url =  (substr($group['url'],0,7) != "http://")?"http://".$user['url']:$user['url'];
				?>	
				<dt>Url</dt>
				<dd><a class="url" href="<?php echo $url; ?>" rel="me nofollow"><?php echo $user['url']; ?></a></dd>
			<?php } ?>	
			<?php if (!empty($user['im'])) { ?>	
				<dt>IM</dt>
				<dd><?php echo $user['im']; ?></dd>
			<?php } ?>	
			<?php if (!empty($user['phone'])) { ?>	
				<dt>Phone</dt>
				<dd class="tel"><?php echo $user['phone']; ?></dd>
			<?php } ?>	
			<?php if (!empty($user['about_me'])) { ?>	
				<dt>Bio</dt>
				<dd class="note"><?php echo $user['about_me']; ?></dd>
			<?php } ?>	
			</dl>
		<?php if (!empty($user['college']) || 
					!empty($user['degree']) || 
					!empty($user['high_school'])) { ?>	
		<h3>Education</h3>
			<dl>
			<?php if (!empty($user['college'])) { ?>	
				<dt>College</dt>
				<dd><?php echo $user['college']; ?></dd>
			<?php } ?>	
			<?php if (!empty($user['degree'])) { ?>	
				<dt>Degree</dt>
				<dd><?php echo $user['degree']; ?></dd>
			<?php } ?>	
			<?php if (!empty($user['high_school'])) { ?>	
				<dt>High School</dt>
				<dd><?php echo $user['high_school']; ?></dd>
			<?php } ?>	
			</dl>
		<?php } ?>
		<?php if (!empty($user['employer']) || 
					!empty($user['position']) || 
					!empty($user['employment_description']) || 
					!empty($user['employment_location']) || 
					!empty($user['employment_time_period'])) { ?>	
		<h3>Employment</h3>
			<dl class="vcard">
			<?php if (!empty($user['employer'])) { ?>
				<dt>Current Employer</dt>
				<dd class="org fn n"><?php echo $user['employer']; ?></dd>
			<?php } ?>	
			<?php if (!empty($user['position'])) { ?>
				<dt>Title</dt>
				<dd class="title"><?php echo $user['position']; ?></dd>
			<?php } ?>	
			<?php if (!empty($user['employment_description'])) { ?>
				<dt>Employment Description</dt>
				<dd class="note"><?php echo $user['employment_description']; ?></dd>
			<?php } ?>	
			<?php if (!empty($user['employment_location'])) { ?>
				<dt>Employment Location</dt>
				<dd class="adr"><?php echo $user['employment_location']; ?></dd>
			<?php } ?>	
			<?php if (!empty($user['employment_time_period'])) { ?>
				<dt>Employment Time Period</dt>
				<dd><?php echo $user['employment_time_period']; ?></dd>	
			<?php } ?>	
			</dl>
		<?php } ?>
	</div>
	<?php } ?>
</div>