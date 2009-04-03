<h2><?php echo $username ?></h2>
<?php if (!empty($User)): ?>
	<?php if ($is_following): ?>
			<h3>Following</h3>
	<?php else: ?>
			<h3><a href="/users/follow/<?php echo $username ?>">Follow</a></h3>
	<?php endif ?>
<?php endif ?>
<?php echo $this->load->view('messages/viewlist') ?>