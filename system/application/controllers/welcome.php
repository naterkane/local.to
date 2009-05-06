<?php

class Welcome extends App_Controller {
	
	/**
	 * Public Timeline 
	 *
	 * @return 
	 */
	function index() {
		$this->layout = 'public';
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

	/**
	 * Displays static views
	 * 
	 * @return 
	 * @param string $view[optional]
	 * @see /system/application/config/routes.php
	 */
	function page($view = null)
	{
		if (empty($view))
			$this->redirect('/');
		
		$this->layout = 'public';
		$this->data['page_title'] = ucfirst($view);
		$this->data['static_view'] = $view;
		//$this->load->view('static/'.$view);
		$this->load->view('users/welcome',$this->data);
	}
}
?>