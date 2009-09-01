<?php
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
 * App Loader
 * 
 * Extended CI_Loader
 * 
 * @package 	Nomcat
 * @subpackage	Libraries
 * @category	Loader
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class App_loader extends CI_Loader
{
	/**
	 * @var array
	 */
	var $helpers = array('Html', 'Time_format', 'Selenium', 'Form', 'Gravatar', 'Avatar');
	/**
	 * @var array
	 */
	var $passData = array();

	/**
	 * Load helpers and instantiate them
	 *
	 * @access public
	 * @return
	 */
	public function loadHelpers()
	{
		foreach ($this->helpers as $helper_class) 
		{		
			$this->loadHelper($helper_class);
		}
	}
	
	/**
	 * Load single helper and instantiate them
	 *
	 * @param string $helper
	 * @access public
	 * @return	
	 */
	public function loadHelper($helper_class = null)
	{
		$ext_helper = APPPATH . 'helpers/' . $helper_class . EXT;
		if (file_exists($ext_helper)) 
		{ 
			include_once($ext_helper);
			if (empty($this->passData[$helper_class])) {
				$helper_class_lc = strtolower($helper_class);
				$this->passData[$helper_class_lc] = new $helper_class();
			}		
		}
	}
	
	/**
	 * Load View
	 *
	 * This extends the view method. It loads helpers and passes off error messages.
	 *
	 * @param	string	$view
	 * @param	array	$vars[optional]
	 * @param	boolean	$return[optional]
	 * @access public
	 * @return	
	 */
	public function view($view, $vars = array(), $return = FALSE)
	{
		$this->passData = $vars;
		$this->loadHelpers();
		parent::view('themes/'.config_item('theme').'/'.$view, $this->passData, $return);
	}
	

}
?>