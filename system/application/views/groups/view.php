<h2><?php echo $name ?></h2>

<h3>
	<?php if ($imAMember): ?>
			<a href="/groups/unsubscribe/<?php echo $name ?>" id="unsubscribe">Unsubscribe</a>
	<?php else: ?>
			<a href="/groups/subscribe/<?php echo $name ?>" id="subscribe">Subscribe</a>
	<?php endif ?>
</h3>
	
<h3>Members</h3>
<ul>
<?php foreach ($members as $member): ?>
	<li>
		<a href="/<?php echo $member ?>"><?php echo $member ?></a> 
		<?php if ($owner == $member): ?>
			(Owner)
		<?php endif ?>
	</li>
<?php endforeach ?>
</ul>

<h3>Messages</h3>