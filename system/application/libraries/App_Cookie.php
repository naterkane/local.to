<?php
/**
* Extended Cookie Class
*/
class App_Cookie {
	
	var $name = 'Auth';
	
	/**
	 * Constructor
	 *
	 * Loads controller into Library, then loads cookie helper and models.
	 *
	 * @todo Check performance on this. See if there is a better way.
	 */
	function __construct() {
		$this->controller =& get_instance();
		$this->controller->load->helper('cookie');
		$this->controller->load->model(array('Cookie_model', 'User'));	
	}

	/**
	 * Delete a cookie
	 */
	function deleteCookie() {
		setcookie($this->name, '', time()-60000, '/');
	}

	/**
	 * Get a user's information
	 *
	 * @return 
	 */	
	function getUser() {
		$username = $this->getUsername();
		if (!empty($username)) {
			return $this->controller->User->find($username);
		} else {
			return null;
		}
	}
	
	function getUsername() {
		$cookie = null;
		if (isset($_COOKIE[$this->name])) {
			$cookie = $_COOKIE[$this->name];
		}
		if ($cookie) {
			return $this->controller->Cookie_model->find($cookie . ':' . $this->name);
		} else {
			return null;
		}
	}
	
	/**
	 * Set a cookie for a user
	 *
	 * Saves a cookie to the browser with the user's encrypted username
	 *
	 * @param string Username
	 */
	function setUser($username) {
		$hashedUsername = sha1(time() . $username . $this->controller->config->item('salt'));
		setcookie($this->name, $hashedUsername, null, '/');
		$this->controller->Cookie_model->save($hashedUsername . ':' . $this->name, $username);		
	}

	/**
	 * Sign out
	 */
	function signOut() {
		$cookie = $_COOKIE[$this->name];
		if ($cookie) {
			$this->deleteCookie();
			$this->controller->Cookie_model->delete($cookie . ':' . $this->name);
		} 
	}

}
?>