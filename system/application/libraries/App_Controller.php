<?php
/**
 * Extended controller
 */
class App_Controller extends Controller {
   
    var $data = array();
	var $layout;				//leave empty for default
    var $postData = array();
    var $userData = array();
   
	/**
	 * Constructor
	 *
	 * Call parent; load libraries, helpers, and models, clean post
	 *
	 * @access public
	 */
    function __construct() 
	{
        parent::Controller();
        $this->load->library(array('Load_helpers'));
        $this->load->model(array('User', 'Message', 'Group'));
		$this->load_helpers->load(array('Html', 'Time', 'Selenium', 'Form'));
        if ($_POST) 
		{
            $this->postData = $this->input->xss_clean($_POST);
        }
    }
 
	/**
	 * Get a users data from cookie
	 *
	 * @access public
	 */ 
    function getUserData() 
	{
        $this->userData = $this->cookie->get('user');
        if (! empty($this->userData)) 
		{
            $this->data['User'] = $this->userData;
        }
    }

	/**
	 * Is the app in testing mode?
	 *
	 * @return boolean
	 * @access public
	 */
	function isTesting()
	{
		return ini_get('display_errors');
	}
 
    /**
     * Checks to see if a user is signed in
     *
     * If not, sends to login
     */
    function mustBeSignedIn() 
	{
        $this->getUserData();
        if ( empty($this->userData)) 
		{
            $this->redirect('/signin', 'You must sign in to view this page.', 'error');
        }
    }
   
    /**
     * Checks to see if a user is not signed in
     */
    function mustNotBeSignedIn() 
	{
        if (! empty($this->userData)) 
		{
            show_404();
        }
    }
   
	/**
	* Random Alpha-Numeric String
	*
	* @param int length
	* @return string 
	* @access public
	*/
	public function randomString($length) 
	{
		$randstr = null;
		srand();
		$chars = array( 'a','b','c','d','e','f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A','B','C','D','E','F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
		for ($rand = 0; $rand < $length; $rand++) 
		{
			$random = rand(0, count($chars) -1);
			$randstr .= $chars[$random];
		}
		return $randstr;
	}

    /**
     * Redirects to given $url
     * Script execution is halted after the redirect.
     *
     * @param mixed $url A string or array-based URL pointing to another location within the app, or an absolute URL
     * @param todo Use CakePHP's redirect here
     * @access public
     */
    function redirect($url, $message=null, $type = null) 
	{
		$this->cookie->setFlash($message, $type);
        header("Location: http://".$_SERVER['HTTP_HOST'].$url, TRUE, 302);
        exit ;
    }

	/**
	 * Send data to html helper
	 *
	 * @todo Would be nice if this were done automatically
	 * @param array $data
	 * @access public
	 */
	function setData($data = array())
	{
		if (!empty($this->form)) 
		{
			foreach ($data as $field => $value) 
			{
				$this->form->data[$field] = $value;
			}
		}
	}

	/**
	 * Send errors to html helper
	 *
	 * @todo Would be nice if this were done automatically
	 * @param array $models[optional]
	 * @access public
	 */
	function setErrors($models = array())
	{
		if (!empty($this->html)) 
		{
			foreach ($models as $model) 
			{
				foreach ($this->$model->validationErrors as $field => $error) 
				{
					$this->form->validationErrors[$field] = $error;
				}
			}
		}
	}
   
}

?>
