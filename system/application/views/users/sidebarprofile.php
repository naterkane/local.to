<h1 id="profile_username"><?php echo (!empty($User['realname']))? $User['realname']: $User['username'];  ?></h1>
<p>
	Following: <?php echo $User['following_count'] ?> 
	Followers: <?php echo $User['follower_count'] ?>
</p>