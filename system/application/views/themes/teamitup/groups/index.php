<div class="box messages">
	<div class="top short">
	<h2>Groups</h2>
	</div>
	<div class="box">
	<div class="block">
		<a href="/groups/add" class="toggler">Create a group</a>
	<?php if (!empty($groups)): ?>
		<ul>
		<?php foreach ($groups as $group): ?>
			<li><img src=""/><a href="/group/<?php echo $group['name'] ?>"><?php echo $group['name'] ?></a></li>		
		<?php endforeach ?>
		</ul>
	<?php else: ?>
			<p>There are no groups yet.</p>
	<?php endif ?>
	</div>
</div>
</div>