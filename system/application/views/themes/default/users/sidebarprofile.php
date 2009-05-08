<a href="/<?php echo $User['username']; ?>" class="image"><? echo $gravatar->img( $User['email'],"48" ); ?></a> <a href="/<?php echo $User['username']; ?>" id="profile_username"><?php echo $User['username'];  ?></a>
<?php echo (!empty($User['location']))? '<p id="profile_location">'.$User['location'].'</p>': "";  ?>
<?php echo (!empty($User['bio']))? '<p id="profile_bio">'.$User['bio'].'</p>': "";  ?>

<?php echo (!empty($User['url']))? '<p id="profile_url"><a href="'.$User['url'].'" rel="me nofollow">'.$User['url'].'</a></p>': "";  ?>

<p>
	Following: <?php echo $User['following_count'] ?> 
	Followers: <?php echo $User['follower_count'] ?>
</p>