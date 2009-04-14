<?php
/**
* Router extension
*/
class App_router extends CI_Router
{
	
	var $config;	
	var $routes 		= array();
	var $error_routes	= array();
	var $class			= '';
	var $method			= 'index';
	var $directory		= '';
	var $uri_protocol 	= 'auto';
	var $default_controller;
	var $scaffolding_request = FALSE; // Must be set to FALSE
	
	
	/**
	 * Validates the supplied segments.  Attempts to determine the path to
	 * the controller.
	 *
	 * @access	private
	 * @param	array
	 * @return	array
	 */	
	function _validate_request($segments)
	{
		// Does the requested controller exist in the root folder?

		if (file_exists(APPPATH.'controllers/'.$segments[0].EXT))
		{
			return $segments;
		} 

		// Is the controller in a sub-folder?
		if (is_dir(APPPATH.'controllers/'.$segments[0]))
		{		
			// Set the directory and remove it from the segment array
			$this->set_directory($segments[0]);
			$segments = array_slice($segments, 1);			
			if (count($segments) > 0)
			{
				// Does the requested controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].EXT))
				{
					show_404($this->fetch_directory().$segments[0]);
				}
			}
			else
			{
				$this->set_class($this->default_controller);
				$this->set_method('index');
			
				// Does the default controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.EXT))
				{
					$this->directory = '';
					return array();
				}
			
			}

			return $segments;
		} 
		else 
		{
			if ((isset($segments[1])) && (isset($segments[2])) && ($segments[1] == 'status')) 
			{
				$username = $segments[0];
				$time = $segments[2];	
				$segments[0] = 'messages';
				$segments[1] = 'view';
				$segments[2] = $username;
				$segments[3] = $time;				
			} 
			else 
			{
				$username = $segments[0];
				$segments[0] = 'users';
				$segments[1] = 'view';			
				$segments[2] = $username;
			}
			return $segments;
		}
	}


}

?>