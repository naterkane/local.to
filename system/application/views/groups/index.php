<h2>Groups (<a href="/groups/add">Add</a>)</h2>
<?php if (!empty($groups)): ?>
	<?php foreach ($groups as $group): ?>
		<p><a href="/group/<?php echo $group ?>"><?php echo $group ?></a></p>
	<?php endforeach ?>
<?php endif ?>