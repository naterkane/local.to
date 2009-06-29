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
 * App Exceptions
 * 
 * Extended CI_Exceptions
 * 
 * @package 	Nomcat
 * @subpackage	Libraries
 * @category	Exceptions
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
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
	 * @param	string	$heading	the heading
	 * @param	string	$message	the message
	 * @param	string	$template[optional]	the template name
	 * @return	string
	 */
	function show_error($heading, $message, $template = 'error_general')
	{
		$message = '<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>';

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();	
		}
		/**
		 * have to include the config library because it's not always available depending on the error context
		 */
		include_once(BASEPATH. 'libraries/Config' . EXT);		
		$config = new CI_Config();
		include(APPPATH.'views/themes/' . $config->item('theme') . '/errors/'. $template . EXT);
		$buffer = ob_get_contents();
		ob_end_clean();
		unset($config);
		return $buffer;
	}
	
	/**
	 * Native PHP error handler
	 *
	 * @param	string	$severity	the error severity
	 * @param	string	$message	the error string
	 * @param	string	$filepath	the error filepath
	 * @param	string	$line		the error line number
	 * @return	string
	 */
	function show_php_error($severity, $message, $filepath, $line)
	{	
		$severity = ( ! isset($this->levels[$severity])) ? $severity : $this->levels[$severity];
	
		$filepath = str_replace("\\", "/", $filepath);
		
		/**
		 * For safety reasons we do not show the full file path
		 */
		if (FALSE !== strpos($filepath, '/'))
		{
			$x = explode('/', $filepath);
			$filepath = $x[count($x)-2].'/'.end($x);
		}
		
		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();	
		}
		include_once(BASEPATH. 'libraries/Config' . EXT);		
		$config = new CI_Config();		
		ob_start();
		include(APPPATH.'views/themes/' . $config->item('theme') . '/errors/error_php' . EXT);
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}
	
	
}
?>