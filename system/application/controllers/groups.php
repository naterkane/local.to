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
		$group = $this->Group->find($this->Group->prefixGroup($name));
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
		$group = $this->Group->find($this->Group->prefixGroup($name));
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
			$this->data['title'] = $name;
			$this->data['name'] = $name;			
			$this->data['members'] = $this->Group->getMembers($name);
			$this->data['owner'] = $this->Group->getOwner($name);
			$this->data['messages'] = $this->Message->getForGroup($name);	
			$this->data['imAMember'] = $this->Group->isAMember($name, $this->userData['username'], $this->data['members']);
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