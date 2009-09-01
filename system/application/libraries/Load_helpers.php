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
 * Load_helpers
 * 
 * @package 	Nomcat
 * @subpackage	Libraries
 * @category	Classes
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Load_helpers {
	
	/**
	 * Array of helpers to load
	 * @var array
	 */
	public $setHelpers = array();
	
	/**
	 * Load helpers and instantiate them
	 *
	 * @param array $helpers array of helpers to load
	 * @access public
	 * @return	
	 */
	public function load($helpers = array())
	{
		$ci= & get_instance();
		foreach ($helpers as $helper_class) 
		{		
			if (isset($this->_ci_helpers[$helper_class])) 
			{
				continue;
			}
			$ext_helper = APPPATH . 'helpers/' . $helper_class . EXT;
			if (file_exists($ext_helper)) 
			{ 
				include_once($ext_helper);
				if (empty($this->setHelpers[$helper_class])) {
					$helper_class_lc = strtolower($helper_class);
					$ci->$helper_class_lc = new $helper_class();
					$this->setHelpers[$helper_class] = true;
				}		
			}
		}
	}
}

?>