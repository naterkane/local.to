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
 * Yielder Hook
 * 
 * Provides simple way to add multiple layouts and theme support
 * 
 * @package 	Nomcat
 * @subpackage	Hooks
 * @category	Hooks
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Yielder {
	
	/**
	 * Specify any helpers that may be required
	 * @var array
	 */
	var $setHelpers = array();
	/**
	 * Specify the layout to be used for the current view
	 * @var string
	 */
	var $layout;
	
	/**
	 * Determines layout to load for view
	 *
	 * @return string
	 */
	function Yield() 
	{
		$ci= & get_instance();
		if (!$ci->layout) {			
			$ci->layout = 'default';
		}
		$layout = BASEPATH ."application/views/themes/".config_item('theme')."/layouts/" . $ci->layout . ".php";
		$current_output = $ci->output->get_output();
		$controller = $ci->uri->segment(1);
		if (file_exists($layout)){
			$output = $ci->load->file($layout, true);
			$output = str_replace("{yield}", $current_output,$output);
			echo $output;
		} else {
			$output = $current_output;
			echo $output;
		}
	}
	
	/**
	 * defines a layout if it was set in the controller
	 * 
	 * <code>$this->layout = "{nameOfTheLayout}"</code>
	 * 
	 * @param object $layout[optional]
	 */
	function setlayout($layout = null){
		if (!empty($layout))
		{
			$ci->layout = $layout;
		}
	}
	
	/**
	 * defines a sidebar to be used if it was set in the controller
	 * 
	 * <code>$this->sidebar = "{directoryOfTheSidebar}/{nameOfTheSidebar}"</code>
	 * 
	 * @param object $layout[optional]
	 */
	function setsidebar($sidebar = null){
		if (!empty($sidebar))
		{
			$ci->sidebar = $sidebar;
		}
	}

}