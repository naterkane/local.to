<div class="box members">
	<h2>Members</h2>
	<?php if (!empty($members) && $members[0] != false): 
		shuffle($members);
		if (count($members)>25)
		{
			$members = array_slice($members,0,25);
		}
		
	?>
	<ul>
		<?php foreach ($members as $member):
		$member = $this->User->get($member);
		?>
		<li><a href="/<?php echo $member['username']; ?>" alt="<?php echo $member['username']; ?>"><?php echo $avatar->user($member, "24" ); ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php else: ?>
		<p>You are not currently following anyone, take a look at the <a href="/public_timeline">public timeline</a> to see if anyone catches your eye.</p>
	<?php endif; ?>
</div>
