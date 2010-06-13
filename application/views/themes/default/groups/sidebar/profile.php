<div class="user-box" id="sb_profile">
	<h3 id="profile_name"><a href="/group/<?php echo $group['name']; ?>">!<?php echo $group['name']; ?></a></h3>
	<address>
	<?php if (!empty($group['location'])) echo "".$group['location'].""; ?></address>
	<?php if (!empty($group['sport'])) echo "<p>".$group['sport']."</p>"; ?>
	<?php if (!empty($group['desc'])) echo "<p>".$group['desc']."</p>"; ?>
	<?php if (!empty($group['url'])){
		 echo "<p><a href=\""; 
		 echo (substr($group['url'],0,7) != "http://")?"http://".$group['url']:$group['url'];
		 echo'">'.$group['url'].'</a></p>'; 
	}?>		
	<ul class="group-stats">
	    <li>
	    		<a href="/groups/members/<?php echo $group['name']; ?>">
	    			<span class="count"><?php echo count($group['members']); ?></span> 
				<span class="label">Members</span>
			</a>
		</li><?php /*
		<li>
			<span class="count">0</span>
			<span class="label">Fans</span>
		</li> */ ?>
		<li>
			<a href="/groups/mentions/<?php echo $group['name'];?>">
				<span class="count"><?php echo count($group['mentions']) ?></span>
				<span class="label">Mentions</span>
			</a>
		</li>
	</ul>
	<p>
		<?php if (!$group['is_owner'] && $this->userData): ?>
			<?php if ($group['im_a_member']): ?>
					<a href="/groups/unsubscribe/<?php echo $group['id']; ?>" id="unsubscribe" class="button" onclick="return confirm('Are you sure you want to leave <?php echo $group['name']; ?>?'); event.returnValue = false; return false;">Leave Team</a>
			<?php elseif (!empty($group['locked']) && $group['locked'] == false):  ?>
					<a href="/groups/subscribe/<?php echo $group['id']; ?>" id="subscribe" class="button">Join <?php echo ucfirst($this->config->item('group')); ?></a>
			<?php endif; ?>
		<?php endif; ?>
	</p>
</div>