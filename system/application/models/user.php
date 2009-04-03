<?php
/**
* User Model
*/
class User extends App_Model {

	/**
	 * Get a users data by username
	 */
	function get($username = null) {
		if ($username) {
			return $this->find($username);
		} else {
			return null;
		}
	}

	function getFollowers($username = null) {
		if ($username) {
			return $this->find($username . ':followers');
		} else {
			return null;
		}
	}
	
	/**
	 * Hash a password
	 *
	 * @param array $password 
	 * @return string 
	 */
	function hashPassword($password) {
		return sha1($password  . $this->config->item('salt'));
	}

	/**
	 * Sign in a user
	 *
	 * @param array $data Userdata
	 * @return mixed Array on success | boolean false on failure
	 */
	function signIn($data) {
		if (!empty($data['username'])) {
			$user = $this->find($data['username']);
			if ((!empty($user)) && ($this->hashPassword($data['password']) == $user['password'])) {
				return $user;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	/**
	 * Create a new user
	 *
	 * @todo Move create and modified to parent
	 * @todo Make return reflect actual save and transactional	
	 * @access public
	 * @param array $data Data to save
	 */
	function signUp($data = array()) {
		$user = array();
		$now = time();
		$this->mode = 'signup';
		$user['username'] = $data['username'];
		$user['password'] = $this->hashPassword($data['password']);
		$user['activated'] = 1;		
		$user['created'] = $now;
		$user['modified'] = $now;
		$message_id = $this->redis->incr("global:nextMessagetId");		
		$this->save($data['username'], $user);
		//$this->redis->push($data['username'] . ':followers', $data['username'], false);
		return true;
	}

	/**
	 * Create a new user
	 *
	 * @todo Add more validation rules: length of pass, length of username, allowed characters, etc
	 * @access public
	 * @param array $data Data to validate
	 */
	/*
	function validate() {
		echo "<pre>";
		print_r($this->data);
		echo "</pre>";
		exit;
		if ($this->mode = 'signup') {
			
		} else {
			return false;
		}
	}*/

}

?>