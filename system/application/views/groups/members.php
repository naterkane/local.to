<h2><a href="/group/<?php echo $name ?>"><?php echo $name ?></a></h2>
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