<h2><?php echo $name ?></h2>

<h3>
	<?php if ($imAMember): ?>
			<a href="/groups/unsubscribe/<?php echo $id ?>" id="unsubscribe">Unsubscribe</a>
	<?php else: ?>
			<a href="/groups/subscribe/<?php echo $id ?>" id="subscribe">Subscribe</a>
	<?php endif ?>
</h3>
	
<h3><a href="/groups/members/<?php echo $name ?>">Members (<?php echo $member_count ?>)</a></h3>

<h3>Messages</h3>

<?php echo $this->load->view('messages/viewlist'); ?>