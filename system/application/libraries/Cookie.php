<?php
/**
* Extended Cookie Class
*/
class Cookie {
	
	var $domain = '/';
	var $expires;
	var $memPrefix = 'cookie';
	var $name = 'Microblog';
	
	
	/**
	 * Constructor
	 *
	 * Loads controller into Library, then loads cookie model.
	 *
	 * @todo Check performance on this. See if there is a better way.
	 */
	function __construct() 
	{
		$this->controller =& get_instance();
		$this->controller->load->model(array('Cookie_model'));
		$this->check();
	}

	/**
	 * Check if cookie is set, if not, set one
	 *
	 * @access public
	 * @return 
	 */
	function check()
	{
		if (!$this->exists()) {
			$this->create();
		}
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
		if ($this->exists()) {
			$this->controller->Cookie_model->delete($this->memPrefix . ':' . $_COOKIE[$this->name]);
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
		return isset($_COOKIE[$this->name]);
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
		if (isset($data[$key])) {
			return $data[$key];
		} else {
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
		if ($this->exists()) {
			return $this->controller->Cookie_model->find($this->memPrefix . ':' . $_COOKIE[$this->name]);
		} else {
			return;
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
		if ($this->exists()) {
			$cookie = $this->getAllData();
			$cookie[$key] = $data;
			return $this->controller->Cookie_model->save($this->memPrefix . ':' . $_COOKIE[$this->name], $cookie);
		}
	}

	/**
	 * Create a new cookie
	 *
	 * @access public	
	 * @return 
	 */
	function create()
	{
		$key = sha1(time() . $this->controller->randomString(10) . $this->controller->config->item('salt'));
		setcookie($this->name, $key, $this->expires, $this->domain);
		$this->set($this->memPrefix . ':' . $key, null);
	}


}
?>