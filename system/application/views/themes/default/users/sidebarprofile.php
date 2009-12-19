<a href="/<?php echo $user['username']; ?>" class="image"><?php echo $avatar->user($user, "48" ); ?></a> <a href="/<?php echo $user['username']; ?>" id="profile_username"><?php echo $user['username'];  ?></a>
<?php echo (!empty($user['location']))? '<p id="profile_location">'.$user['location'].'</p>': "";  ?>
<?php echo (!empty($user['bio']))? '<p id="profile_bio">'.$user['bio'].'</p>': "";  ?>

<?php echo (!empty($user['url']))? '<p id="profile_url"><a href="'.$user['url'].'" rel="me nofollow">'.$user['url'].'</a></p>': "";  ?>

<p>
	<a href="/following/">Following: <?php echo count($user['following']) ?></a>
	<a href="/followers/">Followers: <?php echo count($user['followers']) ?></a>
</p>
