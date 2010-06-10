<div class="user-box vcard" id="sb_profile">
	<a href="/<?php echo $user['username']; ?>" class="thumb-med"><?php echo $avatar->user($user, "36" ); ?></a> 
	<h2><a href="/<?php echo $user['username']; ?>" title="<?php echo $user['username']; ?>"><?php echo $html->name($user);  ?></a></h2>
	<?php echo $html->location($user) ?>
	<?php echo (!empty($user['url']))? '<a href="'.$user['url'].'" class="profile_url" rel="me nofollow">'.$user['url'].'</a>': "";  ?>
	<?php echo (!empty($user['bio']))? '<span class="profile_bio">'.$user['bio'].'</span>': "";  ?>
		<ul class="user-stats">
	    <li>
	      <a href="/following">
	        <span class="count"><?php echo count($user['following']); ?></span>
	        <span class="label">Following</span>
	      </a>
	    </li>
	    <li>
	      <a href="/followers">
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
</div>