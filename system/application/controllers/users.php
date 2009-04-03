<?php
/**
* Class for users controller
*/
class Users extends MY_Controller {
	
	function Users() {
        parent::MY_Controller();
    }
	
	function home() {
		$this->mustBeSignedIn();
		$this->data['title'] = 'Home';
		$this->data['messages'] = $this->Message->getTimeline();
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
				$this->app_cookie->setUser($this->User->modelData['username']);
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
				$this->app_cookie->setUser($user['username']);
				$this->redirect('/users/home');
			}
		} 
		$this->load->view('users/signin', $this->data);
	}
	
	/**
	 * Sign out a user
	 */
	function signout() {
		$this->app_cookie->signOut();
		$this->redirect('/');		
	}
	
}

?>