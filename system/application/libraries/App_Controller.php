<?php
/**
 * Extended controller
 */
class App_Controller extends Controller {
   
    var $data = array();
	var $layout;				//leave empty for default
    var $postData = array();
    var $userData = array();
   
    function __construct() {
        parent::Controller();
        $this->load->library( array ('Redis', 'Cookie', 'Load_helpers'));
        $this->load->model( array ('User', 'Message'));
        if ($_POST) {
            $this->postData = $this->input->xss_clean($_POST);
        }
    }
  
    function getUserData() {
        $this->userData = $this->cookie->getUser();
        if (! empty($this->userData)) {
            $this->data['User'] = $this->userData;
        }
    }

	function isTesting()
	{
		return ini_get('display_errors');
	}
 
    /**
     * Checks to see if a user is signed in
     *
     * If not, sends to login
     */
    function mustBeSignedIn() {
        $this->getUserData();
        if ( empty($this->userData)) {
            $this->controller = & get_instance();
            $this->controller->redirect('/users/signin');
        }
    }
   
    /**
     * Checks to see if a user is not signed in
     */
    function mustNotBeSignedIn() {
        if (! empty($this->userData)) {
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
        header("Location: http://".$_SERVER['HTTP_HOST'].$url, TRUE, 302);
        exit ;
    }
   
}

?>
