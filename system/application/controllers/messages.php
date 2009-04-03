<?php
/**
* Class for users controller
*/
class Messages extends MY_Controller {
	
	function Messages() {
        parent::MY_Controller();
    }
	
	/**
	 * Add a message
	 *
	 * @todo Make ajax
	 */
	function add() {
		$this->mustBeSignedIn();
		if ($this->postData) {
			$this->Message->addMessage($this->postData['message'], $this->userData['username']);
			$this->redirect('/users/home');
		} else {
			show_404();
		}
	}
	
}

?>