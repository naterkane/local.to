<?php

class Welcome extends App_Controller {
	
	/**
	 * Public Timeline 
	 *
	 * @return 
	 */
	function index() {
		$this->getUserData();
		$this->data['title'] = 'Home';
		$this->data['messages'] = $this->Message->getTimeline();
		$this->load->view('messages/public_timeline', $this->data);
	}

}
?>