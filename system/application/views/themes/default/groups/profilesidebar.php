<a href="/<?php echo $group['name']; ?>" class="image"><?php echo $avatar->group($group, "48"); ?></a> <a href="/<?php echo $group['name']; ?>" id="group_name"><?php echo $group['name'];  ?></a>
<?php echo (!empty($group['location']))? '<p id="profile_location">'.$group['location'].'</p>': "";  ?>
<?php echo (!empty($group['bio']))? '<p id="profile_bio">'.$group['bio'].'</p>': "";  ?>

<?php echo (!empty($group['url']))? '<p id="profile_url"><a href="'.$group['url'].'" rel="me nofollow">'.$group['url'].'</a></p>': "";  ?>

<p><?php echo count($group['members']) ?> Members</p>