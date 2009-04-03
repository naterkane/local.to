<?php

class Welcome extends MY_Controller {

	function Welcome() {
        parent::MY_Controller();
				
	}
	
	function index() {
		$this->getUserData();
		$this->data['title'] = 'Home';
		$this->data['messages'] = $this->Message->getTimeline();
		$this->load->view('public_timeline', $this->data);
	}
}

?>