<div class="profile">
<h2><?php echo $username ?></h2>
<?php echo $form->input('user_id', array('value'=>$User['id'], 'type'=>'hidden')) ?>
<?php 
if ((!empty($User)) AND ($User['username'] != $username)) {
	if ($friend_status == 'follow') 
	{
		echo '<h3><a href="/users/follow/' . $username . '" id="follow">Follow</a></h3>';
	} 
	elseif ($friend_status == 'following') 
	{
		echo '<h3><a href="/users/unfollow/' . $username . '" id="unfollow">Unfollow</a></h3>';
	}
	else 
	{
		echo '<h3>Pending a friend request</h3>';
	}
}
echo $this->load->view('messages/viewlist'); 
?>
</div>