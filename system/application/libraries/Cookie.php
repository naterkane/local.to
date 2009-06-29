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
	
	var $cookie;
	var $domain = '/';
	var $expires;
	var $name = 'Microblog';
	var $randomString;
	var $salt;	
	
	/**
	 * Constructor
	 *
	 * Loads controller into Library, then loads cookie model.
	 * 
	 * @see check()
	 * @todo Check performance on this. See if there is a better way.
	 */
	function __construct() 
	{
		$ci = get_instance();
		$ci->load->model(array('Cookie_model'));
		$this->cookie = $ci->Cookie_model;
		$this->salt = $ci->config->item('salt');
		$this->randomString = $ci->randomString(10);		
		unset($ci);
		$this->check();
	}

	/**
	 * Check if cookie is set, if not, set one
	 * 
	 * @see exists()
	 * @see create()
	 * @see getAllData()
	 * @see delete()
	 */
	function check()
	{
		if (!$this->exists()) 
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
	}

	/**
	 * Create a new cookie
	 * 
	 * @see cookie_model
	 * @see cookie_model::create()
	 * @see cookie_model::save()
	 * @see setcookie()
	 */
	function create()
	{
		$cookie = $this->cookie->create();
		$cookie['id'] = sha1(time() . $this->randomString . $this->salt);
		$cookie['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		$cookie['ip'] = $_SERVER['REMOTE_ADDR'];
		$cookie['last_accessed'] = time();		
		setcookie($this->name, $cookie['id'], $this->expires, $this->domain);
		$this->cookie->save($cookie);
	}

	/**
	 * Delete a cookie
	 * 
	 * @see exists()
	 * @see cookie_model
	 * @see cookie_model::delete()
	 */
	function delete() 
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
	function exists()
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
	function flashMessage()
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
	function get($key)
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
	function getAllData()
	{
		if ($this->exists()) 
		{
			return $this->cookie->find($_COOKIE[$this->name]);
		} else {
			return;
		}		
	}

	/**
	 * Unset data from a cookie session
	 *
	 * @access public
	 * @param string $key 
	 * @param mixed $data 	
	 * @return boolean
	 */
	function remove($key)
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
	function set($key, $data)
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
	 * @return 
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

	/**
	 * Set a user data to a session
	 *
	 * @access public
	 * @param array $user User data
	 * @return 
	 * @depreciated
	 */
	public function setUser($user = array())
	{
		$data = array();
		$data['username'] = $user['username'];
		$data['id'] = $user['id'];
		$data['locked'] = $user['locked'];
		$data['activated'] = $user['locked'];	
		$data['threading'] = $user['threading'];	
		$data['email'] = $user['email'];		
		$data['time_zone'] = $user['time_zone'];
		$this->set('user', $data);
	}
	
}
?>