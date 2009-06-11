<?php
/**
 * Extended controller
 */
class App_Controller extends Controller {
   
    public $data = array();
	public $layout = 'default';				//leave empty for default	
	public $sidebar = 'users/sidebarprofile';
	public $params = array();
    public $postData = array();
    public $userData = array();
	public $testingData;
	public $validationErrors = array();
   
	/**
	 * Constructor
	 *
	 * Call parent; load libraries, helpers, and models, clean post
	 *
	 * @access public
	 */
    public function __construct() 
	{
        parent::Controller();
        $this->load->library(array('Load_helpers','Util', 'Sanitize', 'Mail'));
        $this->load->model(array('User', 'Message', 'Group'));
		$_COOKIE = Sanitize::clean($_COOKIE);
		$this->params = $this->uri->params;
		$this->params = Sanitize::clean($this->params, array('odd_spaces'=>false, 'encode'=>false));	
        if ($_POST) 
		{
			$_POST = Sanitize::clean($_POST);
			$this->postData = $_POST;						
        }
		if ($this->testing()) 
		{
			$this->testingData['testing'] = true;
			$this->testingData['count'] = $this->countAllRecords();
		}		
		$this->getUserData();
    }

	public function _avatar($id, $name, $type = 'user')
	{
		$sizes = (is_array($this->config->item('avatar_sizes'))) ? $this->config->item('avatar_sizes') : array(24,36,48,60);
		if (!empty($_FILES)) 
		{
			$this->load->library(array('Uploader', 'Avatar'));
			$this->uploader->upload('avatar', $type . '_' . $id,  $name . '_' . 'original');
			if (!$this->uploader->isError()) 
			{
				$file = $this->uploader->getLastUploadInfo();
				$this->avatar->makeAll($file['dir'], $this->uploader->getName(), $name, $sizes);
			}
			$this->redirect($_SERVER['REQUEST_URI'], $this->uploader->results());
		}
		$this->load->view('users/avatar');
	}

	/**
	 * CheckID 
	 *
	 * @access public
	 * @param $id to check
	 * @return 
	 */
	public function checkId($id = null, $redirect = '/home')
	{
		if (!$id) 
		{
			$this->redirect($redirect);
		}
	}
	
	/**
	 * Check if post exists
	 *
	 * @access public
	 * @return 
	 */
	public function checkPost()
	{
		if (!$this->postData) 
		{
			$this->show404();
		}
	}
 
	/**
	 * Count all records in DB
	 *
	 * @access public
	 * @return int
	 */
	public function countAllRecords()
	{
		$count = count($this->User->tt->fwmkeys('', 1000000));
		return $count;
	}

	/**
	 * Get redirect param from url
	 *
	 * @access public
	 * @param string $default Url to redirect id none is supplied by query string
	 * @return string
	 */
	public function getRedirect($redirect = '/home')
	{
		if (isset($this->params['redirect'])) 
		{
			$redirect = $this->params['redirect'];
		}
		return $redirect;
	}

	/**
	 * Get a users data from cookie
	 *
	 * @access public
	 */ 
    public function getUserData() 
	{
		$user_id = $this->cookie->get('user');
		$this->userData = $this->User->get($user_id);
        if (! empty($this->userData)) 
		{
            $this->data['User'] = $this->userData;
			$this->data['User']['following_count'] = count($this->userData['following']);
			$this->data['User']['follower_count'] = count($this->userData['followers']);
			$this->data['User']['messages_count'] = count($this->userData['private']);
			if (isset($this->userData['time_zone'])) 
			{
				date_default_timezone_set($this->userData['time_zone']);
			}
        }
    }

	/**
	 * Is the app in testing mode?
	 *
	 * @return boolean
	 * @access public
	 */
	public function isTesting()
	{
		return ini_get('display_errors');
	}

    /**
     * Checks to see if a user is signed in
     *
     * If not, sends to login
     */
    public function mustBeSignedIn() 
	{
        if ( empty($this->userData)) 
		{			
            $this->redirect('/signin?redirect=' . urlencode($_SERVER['REQUEST_URI']), 'You must sign in to view this page.', 'error');
        }
    }
   
    /**
     * Checks to see if a user is not signed in
     */
    public function mustNotBeSignedIn() 
	{
        if (! empty($this->userData)) 
		{
            $this->show404();
        }
    }

	/**
	 * Does a key exist in post data?
	 */
	public function postDataKey($key = null)
	{
		if (!$this->postData)
		{
			return false;
		}
		return array_key_exists($key, $this->postData);
	}

	/**
	* Random Alpha-Numeric String
	*
	* @param int length
	* @return string 
	* @access public
	*/
	public public function randomString($length) 
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
    public function redirect($url, $message=null, $type = null) 
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
	public function setData($data = array())
	{
		foreach ($data as $field => $value) 
		{
			$this->data[$field] = $value;
		}
	}

	/**
	 * Send errors to html helper
	 *
	 * @todo Would be nice if this were done automatically
	 * @param array $models[optional]
	 * @access public
	 */
	public function setErrors($models = array())
	{
		foreach ($models as $model) 
		{
			foreach ($this->$model->validationErrors as $field => $error) 
			{
				$this->validationErrors[$field] = $error;
			}
		}
	}

	/**
	 * Show 404
	 *
	 * @access public
	 * @return 
	 */
	public function show404()
	{
		if (empty($this->exceptions)) 
		{
        	$this->load->library(array('Exceptions'));
		}
		$heading = "404 Page Not Found";
		$message = "The page you requested was not found.";
		//log_message('error', '404 Page Not Found --> '.$page);
		echo $this->exceptions->show_error($heading, $message, 'error_404');
		exit;
	}
	
	/**
	 * Is the app in testing mode?
	 *
	 * @access public
	 * @return boolean
	 */
	public function testing()
	{
		return $this->config->item('debug');
	}

}

?>