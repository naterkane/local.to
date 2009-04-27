<div class="profile">
<h2><?php echo $username ?></h2>
<?php 
if ((!empty($User)) AND ($User['username'] != $username)):
	if ($is_following): 
		?><h3><a href="/users/unfollow/<?php echo $username ?>" id="unfollow">Unfollow</a></h3><?php 
	else: 
		?><h3><a href="/users/follow/<?php echo $username ?>" id="follow">Follow</a></h3><?php 
	endif;
endif;
echo $this->load->view('messages/viewlist'); ?>
</div>