<div class="heading">
    <h2><?php echo $page_title; ?></h2>
</div>
<div id="content">
	<?php /*<ul class="subnav">
		<li><?php echo $html->link('Back to stream', '/'.$user['username']) ?></li>
	</ul>
	*/ ?>
	<div class="clearfix"></div>
    <?php if(!empty($users)): ?>
	<div id="users">
        <?php foreach ($users as $u): ?>
	        <?php if(! empty($u)): 
			//var_dump($u);
			$userclasses = null;
				if (!empty($u['locked'])) { $userclasses .= " private"; }
			?>
			<div class="user">
				
				<p class="screenname"><a href="/<?php echo $u['username']?>" class="avatar" title="<?php echo $u['username']; 
						if (!empty($u['locked'])) { ?>'s updates are protected<?php }
						?>"><?php echo $avatar->user($u, "48" ); ?></a>
	        	@<a href="/<?php echo $u['username']; ?>" class="<?php echo $userclasses; ?>" title="<?php echo $u['username']; 
						if (!empty($u['locked'])) { ?>'s updates are protected<?php }
						?>"><?php echo $u['username']; ?></a>
				<span class="right">
				<?php /*if ($this->userData['username'] == $user['username']): ?>
					<a href="/users/unfollow/<?php echo $u['username'] . $html->sendMeHere();?>" class="button">Stop Following</a>		
				<?php else*/
					if ($this->userData && $this->userData['id'] != $u['id']): 
					//var_dump($u['id'],$this->userData);?>
					<?php if (in_array($u['id'],$this->userData['following'])): ?>
					<a href="/users/unfollow/<?php echo $u['username'] . $html->sendMeHere();?>" class="button">Stop Following</a>
					<?php else: ?>
					<a href="/users/follow/<?php echo $u['username'] . $html->sendMeHere();?>" class="button">Follow</a>
					<?php endif; ?>
				<?php endif; ?>
				</span>
				</p>
				<p>
				<?php 
				if (!empty($u['realname'])){  echo $u['realname']; } 
				if (!empty($u['location'])) { echo '<p class="meta">'.$u['location'].'</p>'; }
				?>
				</p>
				<div class="clear"></div>
			</div>
	        <?php endif; ?>
	    <?php endforeach; ?>
		</div>
        <?php echo Page::links($html); ?>
	<?php else: ?>
		<div class="inlineMessage neutral">
			<p>We're sorry but there are no <?php echo $page_title; ?></p>
		</div>
	<?php endif; ?>
</div>
