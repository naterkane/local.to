<a href="/<?php echo $user['username']; ?>" class="image"><?php echo $avatar->user($user, "50" ); ?></a> <div class="meta"><a href="/<?php echo $user['username']; ?>" class="profile_username"><?php echo (!empty($user['realname']))?$user['realname']:$user['username'];  ?></a>
<?php echo (!empty($user['location']))? '<span class="profile_location">'.$user['location'].'</span>': "";  ?>
<?php echo (!empty($user['url']))? '<a href="'.$user['url'].'" class="profile_url" rel="me nofollow">'.$user['url'].'</a>': "";  ?>
<?php echo (!empty($user['bio']))? '<span class="profile_bio">'.$user['bio'].'</span>': "";  ?>
</div>
<div class="stats">
	<table>
		<colgroup span="3"></colgroup>
		<tfoot>
			<tr>
				<th>Following</th>
				<th>Followers</th>
				<th>Posts</th>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td><?php echo count($user['following']); ?></td>
				<td><?php echo count($user['followers']); ?></td>
				<td><?php echo count($user['public']); ?></td>
			</tr>
		</tbody>
	</table>
</div>
