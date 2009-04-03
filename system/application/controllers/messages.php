<?php
/**
* Class for users controller
*/
class Messages extends App_Controller {
	
	/**
	 * Add a message
	 *
	 * @todo Make ajax
	 */
	function add() 
	{
		$this->mustBeSignedIn();
		if ($this->postData) 
		{
			$this->Message->addMessage($this->postData['message'], $this->userData['username']);
			$this->redirect('/users/home');
		} 
		else 
		{
			show_404();
		}
	}
	
	/**
	 * View a single message
	 * 
	 * @return 
	 * @param object $id[optional]
	 */
	function view($id = null)
	{
		$this->load_helpers->load(array('Time'));	
		$this->data['message'] = $this->Message->get($id);
		$this->load->view('messages/viewpost', $this->data);
	}
}

?>