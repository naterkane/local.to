<a href="/<?php echo $group['name']; ?>" class="image"><?php echo $avatar->group($group, "48"); ?></a> 
<a href="/group/<?php echo $group['name']; ?>" id="group_name"><?php echo $group['name'];  ?></a>
<?php if (!empty($group['location'])) echo "<p><strong>Location:</strong> ".$group['location']."</p>"; ?>
<?php if (!empty($group['desc'])) echo "<p>".$group['desc']."</p>"; ?>
<?php if (!empty($group['url'])){
	 echo "<p><a href=\""; 
	 echo (substr($group['url'],0,7) == "http://")?$group['url']:"http://".$group['url'];
	 echo'">'.$group['url'].'</a></p>'; 
}?>		
<p><a href="/groups/members/<?php echo $group['name']; ?>"><?php echo count($group['members']); ?> Members</a> | 0 Fans</p>
<p>
	<?php if (!$group['is_owner'] && $this->userData): ?>
		<?php if ($group['im_a_member']): ?>
				<a href="/groups/unsubscribe/<?php echo $group['id']; ?>" id="unsubscribe" class="toggler">Leave <?php echo ucfirst($this->config->item('group'))?></a>
		<?php elseif (!empty($group['public']) && $group['public'] == true):  ?>
				<a href="/groups/subscribe/<?php echo $group['id']; ?>" id="subscribe" class="toggler">Join <?php echo ucfirst($this->config->item('group'))?></a>
		<?php else: ?>
		    This is a private <?php echo $this->config->item('group')?>. 
		<?php endif; ?>
	<?php endif; ?>
</p>
