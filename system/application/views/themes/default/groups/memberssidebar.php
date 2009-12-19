<div class="box members">
	<h2>Members</h2>
	<div class="block">
	<?php 
	$members = $group['members'];
	if (!empty($members) && $members[0] != false): ?>
	<ul>
		<?php foreach ($members as $member){ 
		$member = $this->User->get($member);
		?>
		<li><a href="/<?php echo $member['username']; ?>" title="<?php echo $member['username']; 
		if ($member['locked'] == (1 || "1")) { ?>'s updates are protected<?php }
		?>"><?php echo $avatar->user($member, "24" ); ?></a></li>
		<?php } ?>
	</ul>
	</div>
	<div class="clear"></div>
	<div><?php echo count($members); ?> Members | <a href="/groups/members/<?php echo $group['name']; ?>">View all</a>
	<?php else: ?>
		<p><?php echo $group['name']; ?> doesn't have anyone on the team yet. If this is your team, why don't you <a href="/groups/invites/<?php echo $group['name']; ?>">invite some teammates</a>?</p>
	<?php endif; ?>
	</div>
</div>