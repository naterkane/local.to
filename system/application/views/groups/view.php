<h2><?php echo $name ?> <?php if ($is_owner): ?>(<a href="/groups/settings/<?php echo $name ?>">Settings</a>)<?php endif ?></h2>

<h3>
	<?php if (!$is_owner): ?>
		<?php if ($imAMember): ?>
				<a href="/groups/unsubscribe/<?php echo $id ?>" id="unsubscribe">Unsubscribe</a>
		<?php else: ?>
				<a href="/groups/subscribe/<?php echo $id ?>" id="subscribe">Subscribe</a>
		<?php endif ?>
	<?php endif ?>
</h3>
	
<h3><a href="/groups/members/<?php echo $name ?>">Members (<?php echo $member_count ?>)</a></h3>

<p><?php if (!empty($url)) echo "<a href=\"$url\">$url</a>";  ?></p>
<p><?php if (!empty($desc)) echo $desc;  ?></p>
<p><?php if (!empty($location)) echo $location;  ?></p>

<h3>Messages</h3>

<?php echo $this->load->view('messages/viewlist'); ?>