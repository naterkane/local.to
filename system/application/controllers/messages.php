<?php
/**
* Class for users controller
*/
class Messages extends App_Controller {
	
	function Messages() {
        parent::App_Controller();
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