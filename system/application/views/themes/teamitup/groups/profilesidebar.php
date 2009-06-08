<a href="/<?php echo $group['name']; ?>" class="thumb-med"><?php echo $avatar->group($group, "36"); ?></a> 
<h2><a href="/<?php echo $group['name']; ?>"><?php echo $group['name'];  ?></a></h2>
<address><?php echo (!empty($group['location']))? $group['location']: "";  ?></address>
<?php echo (!empty($group['bio']))? '<span class="profile_bio">'.$group['bio'].'</span>': "";  ?>

<?php if (!empty($group['url'])){
				 echo '<span class="profile_url"><a href="'; 
				 echo (substr($group['url'],0,7) == "http://")?$group['url']:"http://".$group['url'];
				 echo'" rel="me nofollow">'.$group['url'].'</a></span>'; 
			}?>
<?php echo (!empty($group['desc']))? "<p>".$group['desc']."</p>":""; ?>

<p><a href="/groups/members/<?php echo $group['name'] ?>">Members (<?php echo $group['member_count'] ?>)</a></p>
<?php if (!$group['is_owner'] && $this->userData): ?>
	<?php if ($group['im_a_member']): ?>
			<a href="/groups/unsubscribe/<?php echo $group['id'] ?>" id="unsubscribe" class="btn join-us">Unsubscribe</a>
	<?php else: ?>
			<a href="/groups/subscribe/<?php echo $group['id'] ?>" id="subscribe" class="btn join-us">Subscribe</a>
	<?php endif ?>
<?php endif ?>
<?php if ($group['is_owner']): ?>
<a href="/groups/settings/<?php echo $group['name'] ?>" class="btn join-us">Edit <?php echo (substr($group['name'],-1) == "s")?$group['name']."'":$group['name']."'s"; ?> Profile</a>
<?php endif ?>
</div>