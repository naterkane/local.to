<div class="box">
	<h2><?php echo $name ?> </h2>
	<div class="block">
		<p><a href="/group/<?php echo $name ?>" class="toggler">back to <?php echo $name ?> home</a>			
			<?php if (!$is_owner): ?>
				<?php if ($im_a_member): ?>
						<a href="/groups/unsubscribe/<?php echo $id ?>" id="unsubscribe" class="toggler">Unsubscribe</a>
				<?php else: ?>
						<a href="/groups/subscribe/<?php echo $id ?>" id="subscribe" class="toggler">Subscribe</a>
				<?php endif ?>
			<?php endif ?>
			<?php if ($is_owner): ?>
			<a href="/groups/settings/<?php echo $name ?>" class="toggler">Edit <?php echo (substr($name,-1) == "s")?$name."'":$name."'s"; ?> Profile</a>
			<?php endif ?>
		</p>
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
		<?php echo Page::links($html) ?>
	<?php else:?>
		it looks like there aren't any members yet.
	
	
	<?php endif ?>
	</div>
</div>