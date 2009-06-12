<div class="box messages">
	<div class="top short">
		<h3><?php echo $page_title ?></h3> 
	</div>
	<?php if (!empty($users)): ?>
	<div class="block" id="messages">
		<?php foreach ($users as $user): ?>
			<?php if (!empty($user)): ?>
				<?php echo $user['username']; ?>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php echo Page::links($html); ?>
	</div>
	<?php else: ?>
	<div class="box content"><div class="neutral">
	<h3>Sorry, but we don't have anybody to put here!</h3>
	<h4>People only appear on this page if the user has started to make some friends.</h4>
	<p>If this is you, why don't you go and check out the <a href="/public_timeline">public timeline</a> and reply to others, maybe you can make new a friend or two!</p>		
	</div></div>
	<?php endif; ?>
</div>
