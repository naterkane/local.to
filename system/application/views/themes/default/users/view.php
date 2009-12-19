<div class="heading">
		<h2>
			<?php echo $avatar->user($view_user, "48",true); ?> 
			<span<?php 
					if ($view_user['locked'] == true){ echo ' class="private"';}
					?>><?php echo $html->name($view_user); ?></span>
			<?php echo $form->input('user_id', array('value'=>$view_user['id'], 'type'=>'hidden')); ?>
			<?php 
			if (!empty($this->userData) && $this->userData['username'] != $username):
				if ($friend_status == 'follow') :
					echo '<p class="right"><a href="/users/follow/' . $username . '" id="follow" class="button">Follow @' . $username . '</a></p>';
				elseif ($friend_status == 'following'):
					echo '<p class="right"><a href="/users/unfollow/' . $username . '" id="unfollow" class="button">Stop Following</a></p>';
				else:
					echo '<p>You have a submitted a follow request to '. $username .', it is currently pending.</p>';
				endif;
			endif; ?>
		</h2>
</div>
<div id="content">
	<?php 
	if ($isLocked) 
	{
		?>
		<div class="inlineMessage"><p><strong>This user has locked their status updates.</strong></p></div>
		<?php
	}
	else 
	{
		$this->load->view('messages/viewlist',array('username'=>$username)); 		
	}
	?>
</div>