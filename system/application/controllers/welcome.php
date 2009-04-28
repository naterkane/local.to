<?php

class Welcome extends App_Controller {
	
	/**
	 * Public Timeline 
	 *
	 * @return 
	 */
	function index() {
		$this->getUserData();
		if ($this->userData) 
		{
			$this->redirect('/home');
		} 
		else 
		{
			$this->data['page_title'] = 'Welcome';
			$this->load->view('users/welcome');
		}
	}

}
?>