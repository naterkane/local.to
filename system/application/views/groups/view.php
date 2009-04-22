<h2><?php echo $name ?></h2>

<h3>
	<?php if ($imAMember): ?>
			<a href="/groups/unsubscribe/<?php echo $id ?>" id="unsubscribe">Unsubscribe</a>
	<?php else: ?>
			<a href="/groups/subscribe/<?php echo $id ?>" id="subscribe">Subscribe</a>
	<?php endif ?>
</h3>
	
<h3>Members</h3>
<ul>
<?php foreach ($members as $member): ?>
	<li>
		<a href="/<?php echo $member['username'] ?>"><?php echo $member['username'] ?></a> 
		<?php if ($owner == $member['id']): ?>
			(Owner)
		<?php endif ?>
	</li>
<?php endforeach ?>
</ul>

<h3>Messages</h3>

<?php echo $this->load->view('messages/viewlist'); ?>