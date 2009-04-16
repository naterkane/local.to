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
			if ($this->Group->add($this->postData, $this->userData['username'])) {
				$this->redirect('/group/' . $this->postData['name']);
			} else {
				$this->setErrors(array('Group'));
				$this->cookie->setFlash('There was an error adding your group. Please see below for details.', 'error');
			}
		} 
		$this->load->view('groups/add', $this->data);
	}

	/**
	 * Subscribe to a group
	 *
	 * @access public
	 * @param string $name
	 * @return 
	 */
	function subscribe($name = null)
	{
		$this->mustBeSignedIn();
		$group = $this->Group->find($name);
		if ($group) {
			$this->Group->addMember($name, $this->userData['username']);
			$this->redirect('/group/' . $name);
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
	function unsubscribe($name = null)
	{
		$this->mustBeSignedIn();
		$group = $this->Group->find($name);
		if ($group) {
			$this->Group->removeMember($name, $this->userData['username']);
			$this->redirect('/group/' . $name);
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
		if ($name) {
			$this->getUserData();
			$group = $this->Group->find($name);
			$this->data['title'] = $group['name'];
			$this->data['name'] = $group['name'];		
			$this->data['members'] = $group['members'];
			$this->data['owner'] = $group['owner_id'];
			$this->data['messages'] = $this->Message->getMany($group['messages']);
			$this->data['imAMember'] = $this->Group->isMember($name, $this->userData['username'], $group['members']);
			if ($this->data['members']) {
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