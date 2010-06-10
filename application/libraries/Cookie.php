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
 * Cookie
 * 
 * Extended Cookie class
 * 
 * @package 	Nomcat
 * @subpackage	Libraries
 * @category	Classes
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Cookie {
	
	/**
	 * Cookie Model is stored to this
	 * @var object
	 * @access private	
	 */	
	private $cookie;
	/**
	 * Cookie domain
	 * @var string
	 * @access private	
	 */	
	private $domain = '/';
	/**
	 * last_accessed cookie expires
	 * @var string
	 * @access private	
	 */	
	private $expires;
	/**
	 * Cookie name
	 * @var string
	 * @access private	
	 */	
	private $name = 'local_to_session_data';
	/**
	 * Random String for cookie key
	 * @var boolean
	 * @access private	
	 */	
	private $randomString;
	/**
	 * Salt for encoding
	 * @var string
	 * @access private
	 */	
	private $salt;	
	
	/**
	 * Constructor
	 *
	 * Loads controller into Library, then loads cookie model.
	 * 
	 * @access public
	 */
	public function __construct() 
	{
		log_message('debug', "Cookie Class Initialized");
		$ci = get_instance();
		//echo ;
		//if("assets" == $ci->uri->rsegment(1)){return false;}
		$ci->load->model(array('Cookie_model'));
		$this->expires = time()+60*60*24*30;
		$this->cookie = $ci->Cookie_model;
		$this->salt = $ci->config->item('salt');
		$this->name = strtolower($ci->config->item('sess_cookie_name'))."_session_data";
		$this->randomString = $ci->randomString(10);		
		unset($ci);
		//echo $this->name;
		//echo $this->expires;
		//var_dump($_COOKIE[$this->name]);
		$this->check();
	}

	/**
	 * Check if cookie is set, if not, set one
	 * 
	 * @access public
	 */
	public function check()
	{
		//var_dump($this->getAllData());
		if (! $this->exists()) 
		{
			$this->create();
		}
		else 
		{
			$data = $this->getAllData();
			if (!empty($data['user_agent']) && $_SERVER['HTTP_USER_AGENT'] != $data['user_agent']) 
			{
				$this->delete();
			}
			if (!empty($data['ip']) && $_SERVER['REMOTE_ADDR'] != $data['ip']) 
			{
				$this->delete();
			}
		}
		//var_dump($data);
	}

	/**
	 * Create a new cookie
	 * 
	 * @access public
	 */
	public function create()
	{
	  $ci = get_instance();
    //don't set a cookie if it's url segment has been blacklisted in config
    if(in_array($ci->uri->segment(1),$ci->config->item('cookie_blacklist'))){return false;}
		
    //echo $this->name;
    
    $cookie = $this->cookie->create();
		$cookie['id'] = sha1(time() . $this->randomString . $this->salt);
		$cookie['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$cookie['ip'] = $_SERVER['REMOTE_ADDR'];
		$cookie['last_accessed'] = time();		
		setcookie($this->name, $cookie['id'], $this->expires, $this->domain);
		$this->cookie->save($cookie);
		log_message('debug', "cookie set ".$ci->uri->segment(1));
	}

	/**
	 * Delete a cookie
	 * 
	 * @access public
	 */
	public function delete() 
	{
		setcookie($this->name, '', time()-60000, $this->domain);		
		if ($this->exists()) 
		{
			$this->cookie->delete($_COOKIE[$this->name]);
		}
	}
	
	/**
	 * Does a cookie exist?
	 *
	 * @access public
	 * @return boolean
	 */
	public function exists()
	{
		if (!isset($_COOKIE[$this->name])) 
		{
			return false;
		}
		$cookie = $this->cookie->find($_COOKIE[$this->name]);
		return isset($cookie['id']);
	}
	
	/**
	 * Show flash message
	 * 
	 * @access public
	 * @return string
	 */
	public function flashMessage()
	{
		$return = null;
		if ($this->exists()) 
		{
			$data = $this->getAllData();
			if (!empty($data['flash_message'])) 
			{
				$return = "<div id=\"flashMessage\" class=\"" . $data['flash_type'] . "\">" . $data['flash_message'] . "</div>\n";
				unset($data['flash_message']);
			}
			if (!empty($data['flash_type'])) 
			{
				unset($data['flash_type']);
			}
			$this->cookie->save($data);
		}
		return $return;
	}
	
	
	/**
	 * Get a key's data from the cookie array
	 *
	 * @access public
	 * @param string $key 
	 * @return array|string|null
	 */	
	public function get($key)
	{		
		$data = $this->getAllData();
		if (isset($data[$key])) 
		{
			return $data[$key];
		} 
		else 
		{
			return;
		}
	}

	/**
	 * Get all keys and data stored in a cookie
	 *
	 * @access public
	 * @return array|null
	 */
	public function getAllData()
	{
		if ($this->exists()) 
		{
			return $this->cookie->find($_COOKIE[$this->name]);
		} else {
			return null;
		}		
	}

	/**
	 * Unset data from a cookie session
	 *
	 * @access public
	 * @param string $key 
	 * @return boolean
	 */
	public function remove($key)
	{
		if ($this->exists()) 
		{
			$cookie = $this->getAllData();
			if (isset($cookie[$key])) 
			{
				unset($cookie[$key]);
			}
			return $this->cookie->save($cookie);
		}
	}
	
	/**
	 * Set data to a cookie session
	 *
	 * @access public
	 * @param string $key 
	 * @param mixed $data 	
	 * @return boolean
	 */
	public function set($key, $data)
	{
		if ($this->exists()) 
		{	
			$cookie = $this->getAllData();
			if ($key != 'id') 
			{
				$cookie[$key] = $data;
			}
			return $this->cookie->save($cookie);
		}
	}

	/**
	 * Set Flash
	 *
	 * @access public
	 * @param string $message
	 * @param string $type	
	 */
	public function setFlash($message, $type = null)
	{
		if (!$type) 
		{
			$type = 'success';
		}
		$this->set('flash_message', $message);
		$this->set('flash_type', $type);
	}
	
}