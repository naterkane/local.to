<div class="box members">
	<h2>Members</h2>
	<?php if (!empty($members) && $members[0] != false): ?>
	<ul>
		<?php foreach ($members as $member){ 
		
		?>
		
		<li><a href="/<?php echo $member['username']; ?>" alt="<?php echo $member['username']; ?>"><?php echo $gravatar->img( $member['email'],"24" ); ?></a></li>
		<?php } ?>
	</ul>
	<?php else: ?>
		<p>You are not currently following anyone, take a look at the <a href="/public_timeline">public timeline</a> to see if anyone catches your eye.</p>
	<?php endif; ?>
</div>

<!--
<div class="box">
	<h2><?php echo $name ?> </h2>
	<div class="block">
		<p><a href="/group/<?php echo $name ?>" class="toggler">back to <?php echo $name ?> home</a></p>
	<?php if (!empty($members) && $members[0] != false): ?>
		<table summary="This table includes the members of the <?php echo $name ?> group">
			<colgroup>
				<col class="colA" />
				<col class="colB" />
			</colgroup>
			<thead>
				<tr>
					<th colspan="2" class="table-head">Members</th>
				</tr>
				<tr>
					<th>Name</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($members as $member): ?>
			<tr>
				<td><a href="/<?php echo $member['username'] ?>" id="user_id_<?php echo $member['id'] ?>"><?php echo $member['realname'] ?></a> 
				<?php if ($owner == $member['id']): ?>
					(Owner)
				<?php endif;
				echo (!empty($member['location']))? "<br/>".$member['location']:"";?>
				</td>
				<td><?php
				if ((!empty($User)) AND ($User['username'] != $member['username'])) {
					
					if ($member['friend_status'] == 'follow') 
					{
						echo '<p><a href="/users/follow/' . $member['username'] . '" id="follow" class="toggler">Follow</a></p>';
					} 
					elseif ($member['friend_status'] == 'following') 
					{
						echo '<p><a href="/users/unfollow/' . $member['username'] . '" id="unfollow" class="toggler">Unfollow</a></p>';
					}
					else 
					{
						echo '<p>Request pending.</p>';
					}
					
				}?></td>
			</tr>
		<?php endforeach ?>
			</tbody>
		</table>
	<?php else:?>
		it looks like there aren't any members yet.
	
	
	<?php endif ?>
	</div>
</div>-->