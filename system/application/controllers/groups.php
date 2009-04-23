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
		$this->data['title'] = 'Add a group';
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
			$this->data['name'] = $group['name'];
			$this->data['owner'] = $group['owner_id'];			
			$this->data['members'] = $this->Group->getMembers($group['id']);
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
		if ($group) {
			$this->Group->addMember($group_id, $this->userData['id']);
			$this->redirect('/group/' . $group['name']);
		} else {
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
			$this->data['title'] = $group['name'];
			$this->data['name'] = $group['name'];
			$this->data['id'] = $group['id'];
			$this->data['owner'] = $group['owner_id'];
			$this->data['member_count'] = $this->Group->getMemberCount($group['id']);
			$this->data['messages'] = $this->Message->getForGroup($group['id']);
			$this->data['imAMember'] = $this->Group->isMember($group['id'], $this->userData['id']);
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