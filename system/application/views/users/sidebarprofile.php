<h1 id="profile_username"><?php echo (!empty($User['realname']))?$User['realname']:$User['username'];  ?></h1>
<p>
	Following: <?php echo $following_count ?> 
	Followers: <?php echo $follower_count ?>
</p>