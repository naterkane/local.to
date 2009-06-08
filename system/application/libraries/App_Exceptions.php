<?php
/**
* Exceptions
*/
class App_exceptions extends CI_Exceptions
{
	
	/**
	 * General Error Page
	 *
	 * This function takes an error message as input
	 * (either as a string or an array) and displays
	 * it using the specified template.
	 *
	 * @access	private
	 * @param	string	the heading
	 * @param	string	the message
	 * @param	string	the template name
	 * @return	string
	 */
	function show_error($heading, $message, $template = 'error_general')
	{
		$message = '<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>';

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();	
		}
		//have to include the config library because it's not always available depending on the error context
		include_once(BASEPATH. 'libraries/Config' . EXT);		
		$config = new CI_Config();
		include(APPPATH.'views/themes/' . $config->item('theme') . '/errors/'. $template . EXT);
		$buffer = ob_get_contents();
		ob_end_clean();
		unset($config);
		return $buffer;
	}
	
}
?>