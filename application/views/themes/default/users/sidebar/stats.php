<div class="user-box vcard" id="sb_stats">
	<h3 id="profile_name"><a href="/<?php echo $user['username']; ?>">@<?php echo $user['username']; ?></a></h3>
	<?php echo $html->location($user); ?>
	<?php echo (!empty($user['bio']))? '<p id="profile_bio"><strong>Bio</strong> '.$user['bio'].'</p>': "";  ?>

	<?php echo (!empty($user['url']))? '<p id="profile_url"><a href="'.$user['url'].'" rel="me nofollow">'.$user['url'].'</a></p>': "";  ?>

	<ul class="user-stats">
	    <li>
	      <a href="/users/following/<?php echo $user['username']; ?>">
	        <span class="count"><?php echo count($user['following']); ?></span>
	        <span class="label">Following</span>
	      </a>
	    </li>
	    <li>
	      <a href="/users/followers/<?php echo $user['username']; ?>">
	        <span class="count"><?php echo count($user['followers']); ?></span>
	        <span class="label">Followers</span>
	      </a>
	    </li>
	    <li>
	      <a href="/<?php echo $user['username']; ?>">
	        <span class="count"><?php echo count($user['public']); ?></span>
	        <span class="label">Updates</span>
	      </a>
	    </li>
	</ul>
	<?php if ($this->userData['username']== $user['username']):?>
	<p class="small"><a href="/settings" class="button">Edit Profile Info</a></p>
	<?php endif; ?>
	<?php if (!empty($rss_updates)): ?>
	<p><a href="<?php echo $this->config->item('base_url') ?>rss/user/<?php echo $user['username'] ?>" class="rss">RSS (<?php echo $html->name($user) ?>'s updates)</a></p>		
	<?php endif ?>
</div>