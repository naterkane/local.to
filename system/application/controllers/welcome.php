<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');
/**
 * Nomcat
 *
 * An open source microsharing platform built on CodeIgniter
 *
 * @package		Nomcat
 * @author		NOM
 * @copyright	Copyright (c) 2009, NOM llc.
 * @license		http://creativecommons.org/licenses/by-sa/3.0/
 * @link		http://getnomcat.com
 * @version		$Id$
 * @filesource
 */
/**
 * Welcome
 * 
 * This is the default controller that is loaded for a top level page request.
 * 
 * @package 	Nomcat
 * @subpackage	Helpers
 * @category	Helpers
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Welcome extends App_Controller {

	var $layout = 'public';
	/**
	 * Determine site home page
	 */
	public function index()
	{
		if ($this->userData) 
		{
			$this->redirect('/home');
		}
		elseif ($this->config->item('start_page') == "timeline") //added this to allow for the start page to be defined as "timeline" in the config
		{
			$this->redirect('/public_timeline');			
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
			
		$this->data['page_title'] = ucfirst($view);
		$this->data['static_view'] = $view;	
		if ($this->userData)
		{
			$this->layout = 'default';
			$this->data['User'] = $this->userData;
			$this->load->view('static/'.$view,$this->data);
		}
		else
		{
			$this->data['page_title'] = ucfirst($view);
			$this->data['static_view'] = $view;
			//$this->load->view('static/'.$view);
			$this->load->view('users/welcome',$this->data);
		}
		
	}
	
}
?>