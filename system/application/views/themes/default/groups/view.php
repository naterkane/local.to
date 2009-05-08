<div class="box messages">
	<h2><?php echo $name ?> </h2>
	<div class="box">
		<div class="block">
			<?php
			/**
			 * @todo this should go in the sidebar, not in the main area.
			 */
			// start move to sidebar
			?>
			<p><a href="/groups/members/<?php echo $name ?>">Members (<?php echo $member_count ?>)</a></p>
			<?php if (!empty($url)){
				 echo "<p><a href=\""; 
				 echo (substr($url,0,7) == "http://")?$url:"http://".$url;
				 echo"\">$url</a></p>"; 
			}?>
			<?php if (!empty($desc)) echo "<p>$desc</p>"; ?>
			<?php if (!empty($location)) echo "<p><strong>Location:</strong> $location</p>"; ?>
			<p>
				<?php if (!$is_owner): ?>
					<?php if ($imAMember): ?>
							<a href="/groups/unsubscribe/<?php echo $id ?>" id="unsubscribe" class="toggler">Unsubscribe</a>
					<?php else: ?>
							<a href="/groups/subscribe/<?php echo $id ?>" id="subscribe" class="toggler">Subscribe</a>
					<?php endif ?>
				<?php endif ?>
				
				<?php if ($is_owner): ?>
				<a href="/groups/settings/<?php echo $name ?>" class="toggler">Edit <?php echo (substr($name,-1) == "s")?$name."'":$name."'s"; ?> Profile/a>
				<?php endif ?>
			</p>
			<?php
			// end move to sidebaqr
			?>
			
			<form action="/messages/add" method="post" accept-charset="utf-8">
			<h3>Post a message to <?php echo $name ?></h3>
				<fieldset>
				<?php 
				$messagedata = array();
				$messagedata['message'] = "!".$name;
				echo $this->load->view('messages/postform',$messagedata) ?>
				</fieldset>
			</form>
	
		</div>
	</div>
	<?php echo $this->load->view('messages/viewlist'); ?>
</div>