<div class="heading">
	<h2><?php echo $avatar->group($group, "48"); ?> <span><?php echo $html->groupName($group) ?></span></h2>
</div>
<div id="content">		
	<?php $this->load->view("groups/subnav/group_nav"); ?>	
	<div class="content">
		<?php /* if ($group['is_owner']): ?>
			<p><a href="/groups/invites/<?php echo $group['name'] ?>" class="button">Invite New Members</a></p>
		<?php endif */ ?>
	<?php if (!empty($members) && $members[0] != false): ?>
		<table summary="The members of <?php echo $group['name'] ?>">
			<colgroup>
				<col class="colA" />
				<col class="colB" />								
			</colgroup>
			<thead>
				<tr>
					<th colspan="2" class="table-head">Members</th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($members as $member): 
					$userclass="";
					if (!empty($member['locked'])) { $userclass = "private"; } else  {$userclass="";}
			 ?>
			<tr>
				<td><a style="margin:10px 10px 10px 0;float:left" class="float-left" href="/<?php echo $member['username']; ?>" title="<?php echo $member['username']; ?>"><?php echo $avatar->user($member, "24" ); ?></a>
				<div class="float-left screenname" style="padding-top: 7px;"><a href="/<?php echo $member['username'] ?>" id="user_id_<?php echo $member['id'] ?>" class="<?php echo $userclass ?>"><?php echo $member['username'] ?></a> 
				<?php if ($owner == $member['id']): ?>
					(Owner)
				<?php endif;
				if (!empty($member['realname']) || !empty($member['location'])) { echo "<br/>"; }
				if (!empty($member['realname'])) { echo $member['realname']; }
				if (!empty($member['realname']) && !empty($member['location'])) { echo " | "; }
				if (!empty($member['location'])) { echo $member['location']; }?>
				</div>
				</td>
				<td class="actions">
				<?php
				if ((!empty($User)) AND ($User['username'] != $member['username'])) {
					
					if ($member['friend_status'] == 'follow') 
					{
						echo '<a href="/users/follow/' . $member['username'] . $html->sendMeHere() .'" id="follow" class="button">Follow</a>';
					} 
					elseif ($member['friend_status'] == 'following') 
					{
						echo '<a href="/users/unfollow/' . $member['username'] . $html->sendMeHere() .'" id="unfollow" class="button">Stop Following</a>';
					}
					else 
					{
						echo '<span class="notice">Request pending.</span>';
					}
					
				}
				if (($group['is_owner']) && ($User['id'] != $member['id']))
				{
					echo " ".$html->link('Remove', '/groups/remove/' . $group['id'] . '/' . $member['id'] . $html->sendMeHere(), array('id'=>'remove' . $member['username'],'class'=>'button'), 'Are sure you want to want to remove '.$member['username'].'?');
				}
				if (($group['is_owner']) && ($User['id'] != $member['id']))
				{
					echo " ".$html->link('Blacklist', '/groups/remove/' . $group['id'] . '/' . $member['id'] . '/blacklist' . $html->sendMeHere(), array('id'=>'blacklist' . $member['username'],'class'=>'button'), 'Are sure you want to want to blacklist '.$member['username'].'?');
				}
				?>
				</td>
			</tr>
		<?php endforeach; ?>
			</tbody>
		</table>
		<?php echo Page::links($html); ?>
	<?php else: ?>
		<p class="message"><strong>it looks like there aren't any members yet.</strong></p>
	<?php endif; ?>
	</div>
</div>