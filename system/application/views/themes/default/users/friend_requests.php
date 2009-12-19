<div class="heading">
    <h2>Friend Requests</h2>
</div>
<div id="content">
	<div class="inlineMessage"><p>By choosing to make your profile private, you now must approve all requests for other users to view your updates</p></div>
<?php if (empty($requests)): ?>
	<div class="inlineMessage neutral"><p>You have no friend requests at this time.</p></div>
<?php else: ?>
	<div id="users">
	<?php foreach ($requests as $u):
		
		if(!empty($u)): 
			$userclasses = null;
			if ($u['locked'] == ("1" || 1)) { $userclasses .= " protected"; }
		endif;
		?>
		<div class="user<?php echo $userclasses; ?>">
			<div class="avatar">
			<a href="/<?php echo $u['username']?>" class="image" title="<?php echo $u['username']; 
				if ($u['locked'] == (1 || "1")) { ?>'s updates are protected<?php }
				?>"><?php echo $avatar->user($u, "48" ); ?></a>
			</div>
        	<p><span class="author"><a href="/<?php echo $u['username']; ?>" title="<?php echo $u['username']; 
				if ($u['locked'] == (1 || "1")) { ?>'s updates are protected<?php }
				?>"><strong><?php echo $u['realname']; ?></strong</a> (<?php echo $u['username']; ?>) </span>
			
			<span class="right">
				<a href="/users/confirm/<?php echo $u['username'] . $html->sendMeHere() ?>" class="button" id="confirm<?php echo $u['username'] ?>"><strong>Accept</strong></a>
				<a href="/users/deny/<?php echo $u['username'] . $html->sendMeHere() ?>" class="button" id="deny<?php echo $u['username'] ?>">Deny</a>
			</span></p>
			<p class="meta">
				<?php if (!empty($u['location'])) { echo $u['location'] . "<br/>"; } ?>
				Followers 
				<?php echo (count($u['followers']) != 0) ? '<a href="/followers/'.$u['username'].'">'.count($u['followers']).'</a>' : 0; ?>
				| Following 
				<?php echo (count($u['followers']) != 0) ? '<a href="/following/'.$u['username'].'">'.count($u['following']).'</a>' : 0; ?>
			</p>
			<div class="clear"></div>
		</div>
	<?php endforeach; ?>
	</div>
    <?php echo Page::links($html); ?>
<?php endif; ?>
</div>
