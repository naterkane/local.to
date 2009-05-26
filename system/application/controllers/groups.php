<?php
/**
* Groups
*/
class Groups extends App_Controller
{

	/**
	 * Add a new group
	 *
	 * @access public
	 * @return 
	 */
	public function add()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = 'Add a group';
		if ($this->postData) {
			if ($this->Group->add($this->postData, $this->userData['id'])) {
				$this->redirect('/group/' . $this->postData['name']);
			} else {
				$this->setErrors(array('Group'));
				$this->cookie->setFlash('There was an error adding your group. Please see below for details.', 'error');
			}
		} 
		$this->load->view('groups/add', $this->data);
	}

	/**
	 * View a group's inbox
	 *
	 * @todo Check if group exists
	 * @todo Change that bad hand-off of user data
	 * @todo Merge with view	
	 * @access public
	 * @param string $name	
	 * @return
	 */
	public function inbox($groupname = null)
	{
		$this->mustBeSignedIn();
		if ($groupname) {
			$group = $this->Group->getByName($groupname);
			if (!$this->Group->isMember($group['members'], $this->userData['id'])) 
			{
				show_404();
			}			
			$user = $this->data['User'];
			$this->data = $group; //necessary, but should be removed, this was accidently coded to overwriter user data
			$this->data['page_title'] = $group['name'] . ' Inbox';
			$this->data['groupname'] = $group['name'];
			$this->data['message'] = 'd !' . $group['name'] . ' ';			
			$this->data['is_owner'] = $this->Group->isOwner($this->userData['id'], $group['owner_id']);
			$this->data['member_count'] = count($group['members']);			
			$this->data['messages'] = $this->Message->getMany($group['inbox']);
			$this->data['im_a_member'] = $this->Group->isMember($group['members'], $this->userData['id']);
			$this->data['User'] = $user;
			if ($this->data['member_count'] > 0) {
				$this->load->view('groups/inbox', $this->data);
			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}

	/**
	 * List all groups
	 *
	 * @access public
	 */
	public function index()
	{		
		$this->getUserData();		
		$groups = $this->Group->getAll();
       	$this->data['groups'] = Page::make('Group', $groups['all'], array('method'=>'getMany'));
		$this->load->view('groups/index', $this->data);
	}

	/**
	 * View a group
	 *
	 * @todo Check if group exists
	 * @todo Change that bad hand-off of user data
	 * @todo Merge with inbox		
	 * @access public
	 * @param string $name	
	 * @return
	 */
	public function view($groupname = null)
	{
		$this->getUserData();
		if ($groupname) {
			$group = $this->Group->getByName($groupname);
			if (isset($this->data['User'])) 
			{
				$user = $this->data['User'];
			}
			else 
			{
				$user = array();
			}
			$this->data = $group;
			$this->data['page_title'] = $group['name'];
			$this->data['groupname'] = $group['name'];
			$this->data['is_owner'] = $this->Group->isOwner($this->userData['id'], $group['owner_id']);
			$this->data['member_count'] = count($group['members']);			
			$this->data['messages'] = $this->Message->getMany($group['messages']);
			$this->data['im_a_member'] = $this->Group->isMember($group['members'], $this->userData['id']);
			$this->data['User'] = $user;
			if ($this->data['member_count'] > 0) {
				$this->load->view('groups/view', $this->data);
			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}

	/**
	 * View members
	 *
	 * @todo create a way of getting a users "public" info only and not returning anything that's sensitive.	
	 * @access public
	 * @param string $name
	 * @return 
	 */
	function members($groupname = null, $sidebar = null)
	{
		$this->getUserData();		
		$group = $this->Group->getByName($groupname);
		if ($group) 
		{
			$this->data = $group;
			$this->data['User'] = $this->userData;
			$this->data['is_owner'] = $this->Group->isOwner($this->userData['id'], null, $group['id']);
			$this->data['im_a_member'] = in_array($this->userData['id'], $group['members']);			
			$this->data['page_title'] = $group['name'] . ' Members';
			$this->data['groupname'] = $group['name'];
			$this->data['owner'] = $group['owner_id'];			
        	$this->data['members'] = Page::make('Group', $group['members'], array('method'=>'getMembers'));
			if (empty($sidebar))
			{
				$this->load->view('groups/members', $this->data);
			}
			else{
				return $this->data;
			}						
		} 
		else 
		{
			show_404();
		}
	}

	/**
	 * Update a user profile
	 *
	 * @access public
	 * @return 
	 */
	function settings($groupname = null)
	{
		$this->mustBeSignedIn();
		$user = $this->data['User'];
		$group = $this->Group->getByName($groupname);
		$this->data['page_title'] = 'Group Settings';
		if ($this->Group->isOwner($this->userData['id'], $group['owner_id'])) 
		{
			$this->data = $group;
			$this->data['User'] = $user;
			if ($group) 
			{
				if ($this->postData) 
				{
					if ($this->Group->update($group, $this->postData, $this->userData['id'])) 
					{
						$this->redirect('/groups/settings/' . $this->postData['name'], 'The group was updated.');
					} 
					else 
					{
						$this->setErrors(array('Group'));
						$this->cookie->setFlash('There was an error updating your group. See below for more details.', 'error');
					}
				}
				else 
				{
					$this->setData($this->data);
				}
				$this->load->view('groups/settings', $this->data); 
			} 
			else 
			{
				show_404();
			}
		} 
		else 
		{
			show_404();
		}
	}

	/**
	 * Subscribe to a group
	 *
	 * @access public
	 * @param string $name
	 * @return 
	 */
	function subscribe($group_id = null)
	{
		$this->mustBeSignedIn();
		$group = $this->Group->get($group_id);
		if ($group) 
		{
			$this->Group->addMember($group, $this->userData['id']);
			$this->User->addGroup($this->userData['id'],$group_id);
			$message = 'I just became a member of the group !'. $group['name'];
			$message_id = $this->Message->add($message, $this->userData,false);
			$this->User->sendToFollowers($message_id, $this->userData['followers']);
			$this->redirect('/group/' . $group['name']);
		} 
		else 
		{
			show_404();
		}
	}
	
	/**
	 * Unsubscribe from a group
	 *
	 * @access public
	 * @param string $name
	 * @return 
	 */
	function unsubscribe($group_id = null)
	{
		$this->mustBeSignedIn();
		$group = $this->Group->get($group_id);
		if ($group) {
			$this->Group->removeMember($group, $this->userData['id']);
			$this->User->removeGroup($this->userData['id'],$group_id);
			$this->redirect('/group/' . $group['name']);
		} else {
			show_404();
		}		
	}	
}
?>