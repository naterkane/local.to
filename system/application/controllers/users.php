<?php
/**
* Class for users controller
*/
class Users extends App_Controller {

	/**
	 * Follow a user
	 *
	 * @todo check if user is not yourself
	 * @param string $username
	 */
	function follow($username = null) {
		$this->mustBeSignedIn();
		if ($username) {
			$user = $this->User->get($username);
			if ($user) {
				$this->redis->push('followers:' . $username, $this->userData['username'], false);
				$this->redis->push('following:' . $this->userData['username'], $username, false);				
				$this->redirect('/' . $username);
			} else {
				show_404();
			}
		} else {
			show_404();			
		}
	}
	
	/**
	 * User's home page
	 */
	function home() {
		$this->mustBeSignedIn();
		$this->load_helpers->load(array('Time'));		
		$this->data['title'] = 'Home';
		$this->data['messages'] = $this->Message->getPrivate($this->userData['username']);
		$this->load->view('users/home', $this->data);
	}
	
	/**
	 * Sign up a new user
	 *
	 * @todo Move those load method calls to one place
	 */
	function signup() {
		$this->data['title'] = 'Sign Up';	
		if ($this->postData) {
			if ($this->User->signUp($this->postData)) {
				$this->cookie->setUser($this->User->modelData['username']);
				$this->redirect('/users/home');
			}
		}
		$this->load->view('users/signup', $this->data);
	}

	/**
	 * Sign in a user
	 *
	 * @todo Move those load method calls to one place
	 */	
	function signin() {		
		$this->data['title'] = 'Sign In';
		if ($this->postData) {
			$user = $this->User->signIn($this->postData);
			if (!empty($user)) {
				$this->cookie->setUser($user['username']);
				$this->redirect('/users/home');
			}
		} 
		$this->load->view('users/signin', $this->data);
	}
	
	/**
	 * Sign out a user
	 */
	function signout() {
		$this->cookie->signOut();
		$this->redirect('/');		
	}
	
	/**
	 * View a users public page
	 * 
	 * @param string $username
	 */
	function view($username) {
		$user = $this->User->get($username);
		$messages = $this->Message->getForUser($username);	
		$this->load_helpers->load(array('Time'));	
		if ($user) {
			$this->getUserData();
			$this->data['title'] = 'Home';
			$this->data['username'] = $username;
			$this->data['messages'] = $this->Message->getForUser($username);
			$this->data['is_following'] = $this->User->isFollowing($username, $this->userData['username']);
			$this->load->view('users/view', $this->data);			
		} else {
			show_404();
		}
	}
	
}

?>