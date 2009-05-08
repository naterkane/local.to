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
	function add()
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
	 * List all groups
	 *
	 * @access public
	 */
	function index()
	{		
		$this->getUserData();		
		$this->data['groups'] = $this->Group->getAll();
		$this->load->view('groups/index', $this->data);
	}

	/**
	 * View members
	 *
	 * @access public
	 * @param string $name
	 * @return 
	 */
	function members($name = null)
	{
		$this->getUserData();		
		$group = $this->Group->getByName($name);
		if ($group) 
		{
			$this->data['page_title'] = $group['name'] . ' Members';
			$this->data['name'] = $group['name'];
			$this->data['owner'] = $group['owner_id'];			
			$members = $this->Group->getMembers($group['id']);
			//var_dump($members);
			$this->data['members'] = array();
			foreach ($members as $member)
			{
				/**
				 * lets strip some data that isn't needed out of here. 
				 * 
				 * @todo create a way of getting a users "public" info only and not returning anything that's sensitive.
				 */ 
				$member['password'] = null;
				$member['passwordconfirm'] = null;
				// this isn't really needed
				$member['realname'] = (!empty($member['realname']))? $member['realname'] : $member['username'];
				if ($this->User->isFollowing($member['id'], $this->userData['id'])):
					$member['friend_status'] = 'following';
				elseif ($this->User->isPendingFriendRequest($member['id'], $this->userData['id'])): 
					$member['friend_status'] = 'pending';
				else: 
					$member['friend_status'] = 'follow';				
				endif;
				$this->data['members'][] = $member;
			}
			//var_dump($this->data['members']);
			
			$this->load->view('groups/members', $this->data);						
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
			$this->Group->addMember($group_id, $this->userData['id']);
			$message = 'I just joined !'. $group['name'] . ', <a href="/groups/' . $group["name"] . '">check it out</a>!';
			$message_id = $this->Message->add($message, $this->userData);
			$this->User->sendToFollowers($message_id, $this->userData['id']);
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
			$this->Group->removeMember($group_id, $this->userData['id']);
			$this->redirect('/group/' . $group['name']);
		} else {
			show_404();
		}		
	}	

	/**
	 * View a group
	 *
	 * @todo Check if group exists
	 * @access public
	 * @param string $name	
	 * @return 
	 */
	function view($name = null)
	{
		$this->getUserData();
		if ($name) {
			$this->getUserData();
			$group = $this->Group->getByName($name);
			$user = $this->data['User'];
			$this->data = $group;
			$this->data['page_title'] = $group['name'];
			$this->data['is_owner'] = $this->Group->isOwner($this->userData['id'], $group['owner_id']);
			$this->data['member_count'] = $this->Group->getMemberCount($group['id']);
			$this->data['messages'] = $this->Message->getForGroup($group['id']);
			$this->data['imAMember'] = $this->Group->isMember($group['id'], $this->userData['id']);
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

}
?>