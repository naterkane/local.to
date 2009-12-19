<div class="heading">
		<h2>Teams</h2>
</div>
<div id="content">
	<div class="inlineMessage">
		<p>All of your teams and others on TeamItUp.
		<?php if ($this->userData): ?>
		<a href="/groups/add" class="button">Create a profile for <em>your</em> team</a>
		<?php endif; ?></p>
	</div>
	<?php if (!empty($groups)): ?>
	<div id="teams">	
		<?php foreach ($groups as $group): ?>
		<div class="team">
			<p class="screenname"><a href="/group/<?php echo $group['name']?>" class="avatar"><?php echo $avatar->group($group, "36" ); ?></a>
        	<a href="/group/<?php echo $group['name'] ?>"><?php echo $html->groupName($group) ?></a></p>
			<p class="meta">
				<?php if (!empty($group['sport'])) { ?><?php echo $group['sport'] ?> | <?php } ?>
				<?php if (!empty($group['location'])) { ?><?php echo $group['location'] ?> | <?php } ?>
				<a href="/groups/members/<?php echo $group['name']; ?>">
					<?php echo count($group['members']); ?> 
					<?php echo (count($group['members'])>0)?"Members":"Member"; ?>
				</a> since <?php echo  date('M d Y',$group['created']); ?>
			</p>
			<pre><?php //var_dump($group)?></pre>
			<div class="clear"></div>
		</div>	
		<?php endforeach; ?>
		<?php echo Page::links($html); ?>
	</div>
	<?php else: ?>
	<div class="message">
		<div class="inlineMessage"><p>There are no groups yet.</p></div>
	</div>
	<?php endif; ?>
	
</div>