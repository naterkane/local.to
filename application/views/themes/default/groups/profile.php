<div class="heading">
	<h2><?php echo $avatar->group($group, "48"); ?> <span><?php echo $html->groupName($group) ?></span></h2>
</div>
<div id="content">
	<?php $this->load->view("groups/subnav/group_nav"); ?>	
	<div class="content vcard">
		<?php if (!empty($group['recent_news'])) { ?>	
				<h3>Recent News</h3>
				<p class="inlineMessage"><?php echo $group['recent_news']; ?></p>
				<?php } ?>	
		<h3><?php echo ucfirst($this->config->item('group'))?> Info</h3>
			<dl>
				<dt>Name</dt>
				<dd><span class="fn n"><?php echo $group['fullname']; ?></span> - @<span class="nickname"><?php echo $group['name']; ?></span></dd>
		</dl>
		<h3>Contact Info</h3>
		<dl>
			<?php if (!empty($group['email']) || !empty($group['other_email'])) { ?>
				<dt>Email</dt>
					<?php if (!empty($group['email'])) { ?>	
					<dd class="email"><?php echo $group['email']; ?></dd>
					<?php } ?>	
					<?php if (!empty($group['other_email'])) { ?>	
					<dd class="email"><?php echo $group['other_email']; ?></dd>
					<?php } ?>	
				<?php } ?>
			<dt>Location</dt>
				<dd class="adr"><?php 
					if (!empty($group['location'])) {
						echo $group['location'];
					} else {
						if (!empty($group['address']) || !empty($group['address_line2'])){?><span class="street-address"><?php }
						if (!empty($group['address'])) { echo $group['address']; ?><br /><?php } 
						if (!empty($group['address_line2'])) { echo $group['address_line2']; ?><br /><?php } 
						if (!empty($group['address']) || !empty($group['address_line2'])){?></span><?php }
						if (!empty($group['city'])) { echo '<span class="locality">'. $group['city'] .'</span>';  }
						if (!empty($group['city']) && ($group['state'] || $group['postal_code'])) {
							?>, <?php } 
						if (!empty($group['state'])) { echo '<span class="region">'. $group['state'] .'</span>'; }  
						if (!empty($group['postal_code'])) { echo '<span class="postal-code">'. $group['postal_code'] .'</span>'; } ?> <br/> <?php
						if (!empty($group['country'])) { echo '<span class="country">'. $group['country'] .'</span>'; }	
					} ?></dd>	
			<?php if (!empty($group['url'])) { 
				$url =  (substr($group['url'],0,7) == "http://")?"http://".$group['url']:$group['url'];
				?>	
				<dt>Website</dt>
				<dd><a class="url" href="<?php echo $url; ?>" rel="me nofollow"><?php echo $url; ?></a></dd>
			<?php } ?>	
			<?php if (!empty($group['im'])) { ?>	
				<dt>IM</dt>
				<dd><?php echo $group['im']; ?></dd>
			<?php } ?>	
			<?php if (!empty($group['phone'])) { ?>	
				<dt>Phone</dt>
				<dd class="tel"><?php echo $group['phone']; ?></dd>
			<?php } ?>	
		</dl>
	</div>
</div>