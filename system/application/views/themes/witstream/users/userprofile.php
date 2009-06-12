<p id="profile_name"><strong>Name</strong> <?php echo (!empty($user['realname']))?$user['realname']:$user['username'];  ?></p>
<?php echo (!empty($user['location']))? '<p id="profile_location"><strong>Location</strong> '.$user['location'].'</p>': "";  ?>
<?php echo (!empty($user['bio']))? '<p id="profile_bio"><strong>Bio</strong> '.$user['bio'].'</p>': "";  ?>
<?php echo (!empty($user['url']))? '<p id="profile_url"><a href="'.$user['url'].'" rel="me nofollow">'.$user['url'].'</a></p>': "";  ?>
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
<p><a href="<?php echo $this->config->item('base_url') ?>rss/user/<?php echo $username ?>">RSS feed of <?php echo $username ?>'s updates</a></p>