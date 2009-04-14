<?php
/**
* Load helpers
*/
class Load_helpers {
	
	var $setHelpers = array();
	
	/**
	 * Load helpers and instantiate them
	 *
	 * @todo Make instance dynamic, $myHelper rather than $this->myHelper
	 * @param array $helpers array of helpers to load
	 */
	function load($helpers = array())
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