<?php 
//if user is looking at own profile, show one menu. If not, show another.
if (!empty($group)){
	echo "<ul class=\"menu\" id=\"sb_menu_long\">\n";
	//echo $html->menuItem('Home', '/home', ($this->util->isSection("/home")), $html->unread($user, 'private'));
	if ($group['im_a_member']): 
		echo $html->menuItem("Private Messages", '/group/'.$group['name'], ($this->util->isSection("/group/".$group['name']))); 
		echo $html->menuItem('Direct Messages','/groups/inbox/'.$group['name'], ($this->util->isSection("/groups/inbox/".$group['name'])));
		echo $html->menuItem('Mentions', '/groups/mentions/'.$group['name'], ($this->util->isSection("/groups/mentions/".$group['name'])));
	  if ($this->config->item('dropio_service_enabled') === TRUE):	
		  echo $html->menuItem('File Sharing','/groups/files/'.$group['name'], (($this->util->isSection("/groups/files/".$group['name'])) || $this->util->isSection("/groups/uploadfiles/".$group['name'])));
	  endif;
	else: 
		echo $html->menuItem('Mentions', '/groups/mentions/'.$group['name'], ($this->util->isSection("/groups/mentions/".$group['name'])));
	endif; 
		echo $html->menuItem(ucfirst($this->config->item("group")) . " Info", '/groups/profile/'.$group['name'],($this->util->isSection("/groups/profile/".$group['name'])));
	if ($group['is_owner']) :
		echo $html->menuItem('Edit ' . ucfirst($this->config->item("group")) . ' Info', '/groups/settings/'.$group['name'], ($this->util->isSection("/groups/settings/".$group['name'])));
		echo $html->menuItem('Add/Edit Avatar', '/groups/avatar/'.$group['name'], ($this->util->isSection("/groups/avatar/".$group['name'])));
		echo $html->menuItem('Invite Members', '/groups/invites/'.$group['name'], ($this->util->isSection("/groups/invites/".$group['name'])));
	endif; 
}
else {
	if (!empty($homeMenu) && !$this->isProfile) 
	{
			echo "<ul class=\"menu\" id=\"sb_menu_long\">\n";
			if (!empty($user['friend_requests'])): 
				echo $html->menuItem('Follow Requests', '/friend_requests', ($this->util->isSection("/friend_requests")),count($user['friend_requests']));
			endif;
			echo $html->menuItem('Home', '/home', ($this->util->isSection("/home")), $html->unread($user, 'private'));
			echo $html->menuItem('Mentions', '/replies', ($this->util->isSection("/replies")), $html->unread($user, 'mentions'));
			echo $html->menuItem('Favorites', '/favorites', ($this->util->isSection("/favorites")), $user['favorites']);
			echo $html->menuItem('Private Messages', '/inbox', (($this->util->isSection("/inbox") || $this->util->isSection("/sent")) && (!$this->util->isSection("/group"))),  $html->unread($user, 'inbox'));
			echo $html->menuItem('Everyone', '/public_timeline', $this->util->isSection("/public_timeline"));		
	}
	else
	{
			echo "<ul class=\"menu\" id=\"sb_menu_short\">\n";
			echo $html->menuItem('Full Profile','/users/profile/'. $user['username']."/",$this->util->isSection('/users/profile/'. $user['username']));
			echo $html->menuItem('Updates', '/' . $user['username'], ($this->util->isSection("/" . $user['username']) && !$this->util->isSection("/favorites/") && !$this->util->isSection("users/profile")), $user['public']);
			echo $html->menuItem('Favorites', '/favorites/' . $user['username'], ($this->util->isSection("/favorites/" . $user['username'])), $user['favorites']);
	}
	//print out user groups
	if (!empty($user['groups'])) 
	{
		foreach ($user['groups'] as $_group_id) 
		{
			$_group = $this->Group->get($_group_id);
			if ($_group) 
			{
				if (!empty($homeMenu) && !$this->isProfile) :
					echo $html->menuItem(htmlspecialchars_decode($html->groupName($_group)), '/group/' . $_group['name'], ($this->util->isSection("/group") && $this->util->isSection("/".$_group['name'])), $html->unreadGroup($user, $_group));		
				else:
					echo $html->menuItem(htmlspecialchars_decode($html->groupName($_group)), '/group/' . $_group['name'], ($this->util->isSection("/group") && $this->util->isSection("/".$_group['name'])));		
				endif;
			}
		}
	}
}
?>
</ul>