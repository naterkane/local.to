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
		$count = count($this->uri->segments);
		require_once(APPPATH . '/libraries/Page.php');
		new Page();
		page::$end = page::$offset;
		if (isset($this->uri->segments[$count - 2]) && isset($this->uri->segments[$count - 1])) 
		{
			if (($this->uri->segments[$count - 2] == 'page') && (is_numeric($this->uri->segments[$count - 1])))
			{
				if ($this->uri->segments[$count - 1] > 1) 
				{
					page::$page = $this->uri->segments[$count - 1];
					page::$start = (page::$page - 1) * page::$offset;
					page::$end = (page::$offset * page::$page) - 1;
				}
				unset($this->uri->segments[$count - 2]);
				unset($this->uri->segments[$count - 1]);
			}		
		}
		page::$next = '/' . implode('/', $this->uri->segments) . '/page/';
		page::$next .= page::$page + 1;
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