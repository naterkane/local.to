<?php

class Welcome extends App_Controller {


	/**
	 * Determine site home page
	 */
	public function index()
	{
		if ($this->userData) 
		{
			$this->redirect('/home');
		}
		else 
		{
			$this->redirect('/public_timeline');			
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