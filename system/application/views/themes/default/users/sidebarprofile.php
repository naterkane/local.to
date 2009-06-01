<a href="/<?php echo $user['username']; ?>" class="image"><?php echo $avatar->show($user, "48" ); ?></a> <a href="/<?php echo $user['username']; ?>" id="profile_username"><?php echo $user['username'];  ?></a>
<?php echo (!empty($user['location']))? '<p id="profile_location">'.$user['location'].'</p>': "";  ?>
<?php echo (!empty($user['bio']))? '<p id="profile_bio">'.$user['bio'].'</p>': "";  ?>

<?php echo (!empty($user['url']))? '<p id="profile_url"><a href="'.$user['url'].'" rel="me nofollow">'.$user['url'].'</a></p>': "";  ?>

<p>
	Following: <?php echo count($user['following']) ?> 
	Followers: <?php echo count($user['followers']) ?>
</p>
