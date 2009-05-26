<div class="box">
	<h2>Groups</h2>
	<div class="block">
		<a href="/groups/add" class="toggler">Create a group</a>
	<?php if (!empty($groups)): ?>
		<?php foreach ($groups as $group): ?>
			<p><a href="/group/<?php echo $group['name'] ?>"><?php echo $group['name'] ?></a></p>		
		<?php endforeach ?>
	<?php else: ?>
			<p>There are no groups yet.</p>
	<?php endif ?>
	</div>
</div>