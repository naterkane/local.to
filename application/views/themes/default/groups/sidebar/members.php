<div class="box members" id="sb_members">
	<?php 
	$members = $group['members']; 
	$membercount = count($members);
	?>
	<h3>Members <span class="count"><?php echo $membercount; ?></span></h3>
	<div class="block">
	<?php if (!empty($members) && $members[0] != false): ?>
		<ul>
			<?php foreach ($members as $member){ 
			$member = $this->User->get($member);
			?>
			<li><a href="/<?php echo $member['username']; ?>" title="<?php echo $member['username']; 
			if ($member['locked'] == (1 || "1")) { echo "'s updates are protected"; }
			?>"><?php echo $avatar->user($member, "24" ); ?></a></li>
			<?php } ?>
		</ul>
	</div>
	<div class="clear"></div>
	<div><!--<?php echo $membercount; ?> Member<?php if ($membercount != 1){ ?>s<?php } ?> | --><a href="/groups/members/<?php echo $group['name']; ?>" class="view-all-link">View all</a>
	<?php else: ?>
		<p><?php echo $group['name']; ?> doesn't have anyone on the team yet. If this is your team, why don't you <a href="/groups/invites/<?php echo $group['name']; ?>">invite some teammates</a>?</p>
	<?php endif; ?>
	</div>
</div>