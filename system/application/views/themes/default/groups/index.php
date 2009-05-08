<a href="/groups/add">Create a group</a>
<h2>Groups</h2>
<?php if (!empty($groups)): ?>
	<?php foreach ($groups as $group): ?>
		<p><a href="/group/<?php echo $group ?>"><?php echo $group ?></a></p>		
	<?php endforeach ?>
<?php else: ?>
		<p>There are no groups yet.</p>
<?php endif ?>