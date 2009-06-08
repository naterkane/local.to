<div class="box messages">
	<h2><?php echo $group['name'] ?> </h2>
	<div class="box">
		<div class="block">
			<?php
			/**
			 * @todo this should go in the sidebar, not in the main area.
			 */
			// start move to sidebar
			?>
			<p><a href="/groups/members/<?php echo $group['name'] ?>">Members (<?php echo $group['member_count'] ?>)</a></p>
			<?php if (!empty($url)){
				 echo "<p><a href=\""; 
				 echo (substr($url,0,7) == "http://")?$url:"http://".$url;
				 echo"\">$url</a></p>"; 
			}?>
			<?php if (!empty($group['desc'])) echo "<p>" . $group['desc'] . "</p>"; ?>
			<?php if (!empty($location)) echo "<p><strong>Location:</strong> $location</p>"; ?>
			<p>
				<?php if (!$group['is_owner'] && $this->userData): ?>
					<?php if ($group['im_a_member']): ?>
							<a href="/groups/unsubscribe/<?php echo $group['id'] ?>" id="unsubscribe" class="toggler">Unsubscribe</a>
					<?php else: ?>
							<a href="/groups/subscribe/<?php echo $group['id'] ?>" id="subscribe" class="toggler">Subscribe</a>
					<?php endif ?>
				<?php endif ?>
				
				<?php if ($group['is_owner']): ?>
				<a href="/groups/settings/<?php echo $group['name'] ?>" class="toggler">Edit <?php echo (substr($group['name'],-1) == "s")?$group['name']."'":$group['name']."'s"; ?> Profile</a>
				<?php endif ?>
			</p>
			<?php
			// end move to sidebar
			
			if (isset($group['im_a_member'])): ?>
			<form action="/messages/add<?php echo $html->sendMeHere() ?>" method="post" accept-charset="utf-8">
			<h3>Post a message to <?php echo $group['name'] ?></h3>
				<fieldset>
				<?php 
				$messagedata = array();
				$messagedata['message'] = "!".$group['name'] . " ";
				echo $this->load->view('messages/postform',$messagedata) ?>
				</fieldset>
			</form>
			<?php endif; ?>
		</div>
	</div>
	<?php echo $this->load->view('messages/viewlist'); ?>
</div>