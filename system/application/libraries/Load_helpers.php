<?php
/**
* Load helpes
*/
class Load_helpers {
	
	var $setHelpers = array();
	
	function load($helpers = array())
	{
		$ci= & get_instance();
		foreach ($helpers as $helper_class) {		
			if (isset($this->_ci_helpers[$helper_class])) {
				continue;
			}
			$ext_helper = APPPATH . 'helpers/' . $helper_class . EXT;
			if (file_exists($ext_helper)) { 
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