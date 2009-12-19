	<div class="box following" id="sb_following">
		<h3>Following <span class="count"><?php echo count($user['following']); ?></span></h3>
		<div class="block">
		<?php if (count($user['following']) > 0):
		shuffle($user['following']);
		if (count($user['following']) > 25)
		{
			$user['following'] = array_slice($user['following'],0,25);
		}
		?>
		<ul>
			<?php 
			foreach($user['following'] as $following)
			{
				$following = $this->User->get($following);?>
				<li><a href="/<?php echo $following['username']; ?>" title="<?php echo $following['username']; 
			if ($following['locked'] == (1 || "1")) { ?>'s updates are protected<?php }
			?>"><?php echo $avatar->user($following, "24" ); ?></a></li><?php 
			} 
			?>
		</ul>
		</div>
		<div class="clear"></div>
		<div><a href="/following" class="view-all-link">View all</a>
		<?php else: ?>
			<p><?php echo $html->name($user) ?> is not currently following anyone.</p>
		<?php endif; ?>
		</div>
	</div>