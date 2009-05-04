<?php
/**
* Extended Cookie Class
*/
class Cookie {
	
	public $domain = '/';
	public $expires;
	public $name;
	
	
	/**
	 * Constructor
	 *
	 * Loads controller into Library, then loads cookie model.
	 *
	 * @todo Check performance on this. See if there is a better way.
	 */
	function __construct() 
	{
		$ci = get_instance();
		$this->session = $ci->session;
		$this->config = $ci->config;
		$this->name = $this->config->item('sess_cookie_name');
		unset($ci);
	}

	/**
	 * Delete a cookie
	 *
	 * @access public
	 * @return
	 */
	function delete() 
	{
		setcookie($this->name, '', time()-60000, $this->domain);
	}
	
	/**
	 * Does a cookie exist?
	 *
	 * @access public	
	 * @return boolean
	 */
	function exists()
	{
		return isset($_COOKIE[$this->name]);
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
		$message = $this->get('microblog_message');
		$type = $this->get('microblog_type');	
		$this->remove('microblog_message');
		$this->remove('microblog_type');		
		if (($message) && ($type))
		{
			$return = "<div id=\"flashMessage\" class=\"$type\">$message</div>\n";
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
		return $this->session->userdata($key);
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
		return $this->session->unset_userdata($key);
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
		return $this->session->set_userdata($key, $data);
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
		$this->set('microblog_message', $message);
		$this->set('microblog_type', $type);		
	}

	/**
	 * Set a user data to a session
	 *
	 * @access public
	 * @param array $user User data
	 * @return 
	 */
	public function setUser($user = array())
	{
		$data = array();
		$data['username'] = $user['username'];
		$data['id'] = $user['id'];
		$data['locked'] = $user['locked'];
		$data['activated'] = $user['locked'];		
		$data['email'] = $user['email'];		
		$data['time_zone'] = $user['time_zone'];
		$this->set('user', $data);
	}
	

}
?>