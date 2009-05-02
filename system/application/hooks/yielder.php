<?php
class Yielder {
	
	var $setHelpers = array();
	var $layout;
	/**
	 * Determines layout to load for view
	 *
	 * @return
	 */
	function Yield() 
	{
		$ci= & get_instance();
		if (!$ci->layout) {			
			$ci->layout = 'default';
		}
		$layout = BASEPATH ."application/views/layouts/" . $ci->layout . ".php";
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
	 * <code>$this->layout = "{nameOfTheLayout}"</code>
	 * 
	 * @return 
	 * @param object $layout[optional]
	 */
	function setlayout($layout = null){
		if (!empty($layout))
		{
			$ci->layout = $layout;
		}
	}

}
?>