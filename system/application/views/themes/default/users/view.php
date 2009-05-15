<div class="messages box">
	<h2><?php
		$author = $this->User->getByUsername($username);
		echo $gravatar->img( $author['email'],"60" ); ?>
		<?php echo $username; ?></h2>
	<?php echo $form->input('user_id', array('value'=>$User['id'], 'type'=>'hidden'));
	if (!empty($User) && $User['username'] != $username) {
		?><div class="box"><div class="block"><?php
		if ($friend_status == 'follow') 
		{
			echo '<p><a href="/users/follow/' . $username . '" id="follow" class="toggler">Follow</a></p>';
		} 
		elseif ($friend_status == 'following') 
		{
			echo '<p><a href="/users/unfollow/' . $username . '" id="unfollow" class="toggler">Unfollow</a></p>';
		}
		else 
		{
			echo '<p>You have a submitted a friend request to '. $username .', it is currently pending.</p>';
		}
		?></div></div><?php
	}
	?>
	<?php $this->load->view('messages/viewlist'); ?>
	<?php $this->load->view('static/toggle_threading'); ?>
	<div class="clear"></div>
</div>