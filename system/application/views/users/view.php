<h2><?php echo $username ?></h2>
<?php 
if (!empty($User)): 
	if ($User['username'] == $username): 
		// do nothing
	elseif ($is_following): 
		?><h3>Following</h3><?php 
	else: 
		?><h3><a href="/users/follow/<?php echo $username ?>" id="follow">Follow</a></h3><?php 
	endif;
endif;

echo $this->load->view('messages/viewlist'); ?>