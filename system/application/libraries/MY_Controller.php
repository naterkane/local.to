<?php
/**
* Extended controller
*/
class MY_Controller extends Controller {
	
	var $data = array();
	var $postData = array();
	var $userData = array();
	
	function getUserData() {
		$this->userData = $this->app_cookie->getUser();
		if (!empty($this->userData)) {
			$this->data['User'] = $this->userData;
		}
	}
	
	function MY_Controller() {	
		parent::Controller();	
		$this->load->library(array('Redis'));
		$this->load->model(array('User', 'Message'));	
		if ($_POST) {
			$this->postData = $this->input->xss_clean($_POST);
		}
	}
	
	/**
	 * Checks to see if a user is signed in
	 *
	 * If not, sends to login
	 */
	function mustBeSignedIn() {
		$this->getUserData();
		if (empty($this->userData)) {
			$this->controller =& get_instance();			
			$this->controller->redirect('/users/signin');
		}
	}
	
	/**
	 * Checks to see if a user is not signed in
	 */
	function mustNotBeSignedIn() {
		if (!empty($this->userData)) {
			show_404();
		}
	}
	
	/**
	 * Redirects to given $url
	 * Script execution is halted after the redirect.
	 *
	 * @param mixed $url A string or array-based URL pointing to another location within the app, or an absolute URL
	 * @param todo Use CakePHP's redirect here
	 * @access public
	 */
	function redirect($url) {
			header("Location: http://" . $_SERVER['HTTP_HOST'] . $url, TRUE, 302); 
			exit;
	}
	
}

?>