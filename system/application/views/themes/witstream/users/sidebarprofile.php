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
				<th><a href="/following">Following</a></th>
				<th><a href="/followers">Followers</a></th>
				<th><a href="/<?php echo $user['username']; ?>">Updates</a></th>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td><a href="/following"><?php echo count($user['following']); ?></a></td>
				<td><a href="/followers"><?php echo count($user['followers']); ?></a></td>
				<td><a href="/<?php echo $user['username']; ?>"><?php echo count($user['public']); ?></a></td>
			</tr>
		</tbody>
	</table>
</div>
