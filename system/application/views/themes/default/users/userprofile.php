<p id="profile_name"><strong>Name</strong> <?php echo (!empty($user['realname']))?$user['realname']:$user['username'];  ?></p>
<?php echo (!empty($user['location']))? '<p id="profile_location"><strong>Location</strong> '.$user['location'].'</p>': "";  ?>
<?php echo (!empty($user['bio']))? '<p id="profile_bio"><strong>Bio</strong> '.$user['bio'].'</p>': "";  ?>

<?php echo (!empty($user['url']))? '<p id="profile_url"><a href="'.$user['url'].'" rel="me nofollow">'.$user['url'].'</a></p>': "";  ?>

<p>
	<a href="/users/following/<?php echo $user['username']; ?>">Following: <?php echo count($user['following']) ?></a>
	<a href="/users/followers/<?php echo $user['username']; ?>">Followers: <?php echo count($user['followers']) ?></a>
</p>
<p><a href="/users/profile/<?php echo $user['username']; ?>">View Full Profile</a></p>
<p><a href="<?php echo $this->config->item('base_url') ?>rss/user/<?php echo $username ?>">RSS feed of <?php echo $username ?>'s updates</a></p>