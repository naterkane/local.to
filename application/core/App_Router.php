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
 * App_Router
 * 
 * Router Extension
 * 
 * @package 	Nomcat
 * @subpackage	Libraries
 * @category	Router
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class App_router extends CI_Router
{
	
	/**
	 * Class to load
	 * @var string
	 */
	public $class;	
	/**
	 * Config to load
	 * @var sting
	 */	
	public $config;	
	/**
	 * Name of controller
	 * @var sting
	 */	
	public $default_controller;	
	/**
	 * Directory to search
	 * @var sting
	 */	
	public $directory;
	/**
	 * @var array
	 */
	public $error_routes = array();	
	/**
	 * @var string
	 */
	public $method = 'index';
	/**
	 * @var array
	 */
	public $routes = array();	
	/**
	 * This must be set to FALSE
	 * @var boolean
	 */
	public $scaffolding_request = FALSE;
	/**
	 * @var string
	 */
	public $uri_protocol = 'auto';	

	/**
	 * 
	 * @see Page
	 * @see Page::setup()
	 * @see CI_Router
	 * @see CI_Router::_parse_routes()
	 * @access public
	 */
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
	 * @access	public
	 * @param	array	$segments
	 * @return	array
	 */	
public function _validate_request($segments)
  {
    // are we trying to view a specific page?
    if(count($segments) > 2 && $segments[count($segments)-2]=="page" && is_numeric($segments[count($segments)-1]))
    {
      $segments = array_slice($segments,0,count($segments)-2);
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
        if (! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].EXT))
        {
          show_404($this->fetch_directory().$segments[0]);
        }
      }
      else
      {
        //$this->set_class($this->default_controller);
        //$this->set_method('index');
        // Is the method being specified in the route?
        if (strpos($this->default_controller, '/') !== FALSE)
        {
          $x = explode('/', $this->default_controller);
          
          $this->set_class($x[0]);
          $this->set_method($x[1]);
        }   
        else
        {
          $this->set_class($this->default_controller);
          $this->set_method('index');
        }
      
        // Does the default controller exist in the sub-folder?
        if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.EXT))
        {
          $this->directory = '';
          return array();
        }
      
      }

      return $segments;
    } 
    //else 
    //{
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
        // do nothing
      }
      else 
      {
        $username = $segments[0];
        $segments[0] = 'users';
        $segments[1] = 'view';      
        $segments[2] = $username;
      }
      return $segments;
    //}
  }
}