<!--<a href="/<?php echo $user['username']; ?>" class="image"><?php echo $gravatar->img( $user['email'],"48" ); ?></a> -->
<?php //$user = $this->User->getByUsername($username); ?>
<p id="profile_name"><strong>Name</strong> <?php echo (!empty($user['realname']))?$user['realname']:$user['username'];  ?></p>
<?php echo (!empty($user['location']))? '<p id="profile_location"><strong>Location</strong> '.$user['location'].'</p>': "";  ?>
<?php echo (!empty($user['bio']))? '<p id="profile_bio"><strong>Bio</strong> '.$user['bio'].'</p>': "";  ?>

<?php echo (!empty($user['url']))? '<p id="profile_url"><a href="'.$user['url'].'" rel="me nofollow">'.$user['url'].'</a></p>': "";  ?>

<p>
	Following: <?php echo count($user['following']); ?> 
	Followers: <?php echo count($user['followers']);?>
</p>

<p><a href="<?php echo $this->config->item('base_url') ?>rss/user/<?php echo $username ?>">RSS feed of <?php echo $username ?>'s updates</a></p>