<?php /*<ul class="subnav">
	<?php if ($group['im_a_member']): ?>
	<li<?php echo ($this->util->isSection("/group/".$group['name']))?' class="current"':""; ?>><a href="/group/<?php echo $group['name']; ?>"><?php echo ucfirst($this->config->item("group")) ?> Stream</a></li>
	<li<?php echo ($this->util->isSection("/groups/mentions/".$group['name']))?' class="current"':""; ?>><a href="/groups/mentions/<?php echo $group['name']; ?>">Mentions</a></li>
	<li<?php echo ($this->util->isSection("/groups/inbox/".$group['name']))?' class="current"':""; ?>><a href="/groups/inbox/<?php echo $group['name']; ?>">Private Messages</a></li>
	<?php else: ?>
	<li<?php echo ($this->util->isSection("/group/".$group['name']))?' class="current"':""; ?>><a href="/group/<?php echo $group['name']; ?>">Mentions</a></li>
	<?php endif; ?>
	<li<?php echo ($this->util->isSection("/groups/profile/".$group['name']))?' class="current"':""; ?>><a href="/groups/profile/<?php echo $group['name']; ?>"><?php echo ucfirst($this->config->item("group")) ?> Info</a></li>
	<?php if ($group['is_owner']) : ?>
	<li<?php echo ($this->util->isSection("/groups/settings/".$group['name']))?' class="current"':""; ?>><a href="/groups/settings/<?php echo $group['name']; ?>">Edit <?php echo ucfirst($this->config->item("group")) ?> Settings</a></li>
	<li<?php echo ($this->util->isSection("/groups/avatar/".$group['name']))?' class="current"':""; ?>><a href="/groups/avatar/<?php echo $group['name']; ?>">Add/Edit Avatar</a></li>
	<li<?php echo ($this->util->isSection("/groups/invites/".$group['name']))?' class="current"':""; ?>><a href="/groups/invites/<?php echo $group['name']; ?>">Invite Members</a></li>
	<?php endif; ?>
</ul>
<div class="clearfix"></div>
*/ ?>