<p id="profile_name"><strong>Name</strong> <?php echo (!empty($user['realname']))?$user['realname']:$user['username'];  ?></p>
<?php echo (!empty($user['location']))? '<p id="profile_location"><strong>Location</strong> '.$user['location'].'</p>': "";  ?>
<?php echo (!empty($user['bio']))? '<p id="profile_bio"><strong>Bio</strong> '.$user['bio'].'</p>': "";  ?>

<?php echo (!empty($user['url']))? '<p id="profile_url"><a href="'.$user['url'].'" rel="me nofollow">'.$user['url'].'</a></p>': "";  ?>

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

<p><a href="<?php echo $this->config->item('base_url') ?>rss/user/<?php echo $username ?>">RSS feed of <?php echo $username ?>'s updates</a></p>