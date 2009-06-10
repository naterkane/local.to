<?php
/**
* Router extension
*/
class App_router extends CI_Router
{
	
	public $class;	
	public $config;	
	public $default_controller;	
	public $directory;
	public $error_routes = array();	
	public $method = 'index';
	public $routes = array();	
	public $scaffolding_request = FALSE; // Must be set to FALSE
	public $uri_protocol = 'auto';	

	public function _parse_routes()
	{
		require_once(APPPATH . '/libraries/Page.php');
		Page::setup($this->uri->segments);
		parent::_parse_routes();
	}
	
	/**
	 * Validates the supplied segments.  Attempts to determine the path to
	 * the controller.
	 *
	 * @access	private
	 * @param	array
	 * @return	array
	 */	
	public function _validate_request($segments)
	{
		// are we trying to view a specific page?
		if(count($segments) > 2 && $segments[count($segments)-2]=="page" && is_numeric($segments[count($segments)-1]))
		{
			var_dump($segments);
			echo "<br>";
			$segments = array_slice($segments,0,count($segments)-2);
			var_dump($segments);
			exit;
		}

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
			elseif (isset($segments[1]) && isset($segments[2]) && ($segments[1] == 'page') && is_numeric($segments[2]))
			{
				
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