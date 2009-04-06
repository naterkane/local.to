<?php

class Welcome extends App_Controller {
	
	/**
	 * 
	 * @return 
	 */
	function index() {
		$this->getUserData();
		$this->load_helpers->load(array('Time'));
		$this->data['title'] = 'Home';
		$this->data['messages'] = $this->Message->getTimeline();
		$this->load->view('public_timeline', $this->data);
	}

}
?>