<?php 
echo $this->load->view('groups/sidebar/profile');	
if (!empty($group['im_a_member'])) 
{
	$this->load->view('users/sidebar/menu', array('group'=> $group, 'homeMenu'=>true));		
}
echo $this->load->view('groups/sidebar/members');