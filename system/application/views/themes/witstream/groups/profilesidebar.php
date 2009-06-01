<a href="/<?php echo $group['name']; ?>" class="image"><?php echo $avatar->group($group, "50" ); ?></a> 
<div class="meta"><a href="/<?php echo $group['name']; ?>" class="group_name"><?php echo $group['name'];  ?></a>
<?php echo (!empty($group['location']))? '<span class="profile_location">'.$group['location'].'</span': "";  ?>
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
							<a href="/groups/unsubscribe/<?php echo $group['id'] ?>" id="unsubscribe" class="toggler">Unsubscribe</a>
					<?php else: ?>
							<a href="/groups/subscribe/<?php echo $group['id'] ?>" id="subscribe" class="toggler">Subscribe</a>
					<?php endif ?>
				<?php endif ?>
				<?php if ($group['is_owner']): ?>
				<a href="/groups/settings/<?php echo $group['name'] ?>" class="toggler">Edit <?php echo (substr($group['name'],-1) == "s")?$group['name']."'":$group['name']."'s"; ?> Profile</a>
				<?php endif ?>
</div>