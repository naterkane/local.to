<?php
/**
 * Nomcat
 *
 * An open source microsharing platform built on CodeIgniter
 *
 * @package		Nomcat
 * @author		NOM
 * @copyright	Copyright (c) 2009, NOM llc.
 * @license		http://creativecommons.org/licenses/by-sa/3.0/
 * @link		http://getnomcat.com
 * @version		$Id$
 * @filesource
 */
 /**
 * App_Controller Controller
 * 
 * Extended Controller
 * 
 * @package 	Nomcat
 * @subpackage	Libraries
 * @category	Controllers
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class App_Controller extends Controller {

	/**
	 * @var array
	 */
    public $data = array();
	/**
	 * @var boolean
	 */
    public $isSignedIn = false;
	/**
	 * @var boolean
	 */
    public $isProfile = false;
	/**
	 * The layout to use for any given view. Refers to filename in <code>theme/layouts</code> directory
	 * @var string
	 */
	public $layout = 'default';
	/**
	 * Whether or not to load the group sidebar
	 * @var string
	 */	
	public $loadGroupSidebar = false;
	/**
	 * @var array
	 */
	public $params = array();
	/**
	 * @var array
	 */
    public $postData = array();
	/**
	 * @var array
	 */
    public $userData = array();
	/**
	 * @var string
	 */
	public $testingData;
	/**
	 * @var array
	 */
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
		$this->load->config('fireignition');
		if ($this->config->item('fireignition_enabled') == true)
		{
			if (floor(phpversion()) < 5)
			{
				log_message('error', 'PHP 5 is required to run fireignition');
			} else {
				$this->load->library('firephp');
				$this->firephp->setOptions(array('includeLineNumbers' => true));
			}
		}
		else 
		{
			$this->load->library('firephp_fake');
			$this->firephp =& $this->firephp_fake;
		}
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
		if (!empty($this->userData)) //set threading 
		{
			$this->Message->threaded = $this->userData['threading'];
		}
    }

	/**
	 * Set a reply to link
	 *
	 * @access protected
	 * @param int $replyTo
	 * @return 
	 */
	protected function setReplyTo($replyTo = null)
	{
		if ($replyTo) 
		{
			$this->Message->findParent($replyTo);
			$message = $this->Message->getOne($replyTo);	
			$parent = $this->Message->getParent();
			if (!empty($parent['id'])) 
			{
				$this->data['reply_to'] = $parent['id'];
			}
			else 
			{
				$this->data['reply_to'] = $message['id'];				
			}
			if (!empty($message['User'])) 
			{
				$this->data['reply_to_username'] = $parent['User']['username'];
				$this->data['message'] = '@' . $message['User']['username'] . ' ';
			}
		}
	}
	

	/**
	 * Handles the loading of the Uploader and Avatar classes and handles the files
	 * 
	 * This method is used for both {@link users::avatar()} and {@link groups::avatar()}
	 * 
	 * @param int $id
	 * @param string $name
	 * @param string $type[optional]
	 */
	public function _avatar($id, $name, $type = 'user')
	{
		$sizes = (is_array($this->config->item('avatar_sizes'))) ? $this->config->item('avatar_sizes') : array(24,36,48,60);
		$this->data['avatartype'] = $type;
		$this->data['avatarid'] = $id;
		$this->data['name'] = $name;
		//var_dump($this->data);
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
		$this->load->view($type.'s/avatar', $this->data);
	}

	/**
	 * CheckID 
	 * Make sure an id is present. Used in controller methods that rely on a passed variable.
	 * @access public
	 * @param $id to check
	 * @param $redirect Url to redirect to if id not present. Defaults to home.
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
	 * Check if data has been post from a form
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
	 * @return int Number of records
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
	 * @param boolean[optional] $force Flag to allow user to either force redirection to a specified url, or to the HTTP_REFERRER if available.
	 * @param string[optional] $default Url to redirect id none is supplied by query string
	 * @return string
	 */
	public function getRedirect($force = false,$redirect = '/')
	{
		if (isset($this->params['redirect'])) 
		{
			$redirect = $this->params['redirect'];
		} 
		elseif (true == $force && '/' !== $redirect){
		}
		elseif (!empty($_SERVER['HTTP_REFERER']) && true == $force)
		{
			$redirect = $_SERVER['HTTP_REFERER'];
		}		
		return $redirect;
	}

	/**
	 * Get a users data from cookie
	 *
	 * @access public
	 * @return
	 */ 
    public function getUserData() 
	{
		$user_id = $this->cookie->get('user');
		$this->userData = $this->User->get($user_id);
        if (! empty($this->userData)) 
		{
			$this->isSignedIn = true;
            $this->data['User'] = $this->userData;
			$this->data['User']['following_count'] = count($this->userData['following']);
			$this->data['User']['follower_count'] = count($this->userData['followers']);
			$this->data['User']['messages_count'] = count($this->userData['private']);
			if (isset($this->userData['time_zone'])) 
			{
				date_default_timezone_set($this->userData['time_zone']);
			}
			$this->data['homeMenu'] = true;			
        }
		$this->data['profile'] = $this->userData;
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
	 * @access public
	 * @return	
     */
    public function mustBeSignedIn() 
	{
        if (empty($this->userData)) 
		{
            $this->getUserData();
			if (empty($this->userData)) 
			{
				$this->redirect('/signin?redirect=' . urlencode($_SERVER['REQUEST_URI']), 'You must sign in to view this page.', 'error');
			}
        }
    }

    /**
     * Checks to see if a user is not signed in
     *
     * If so, sends to home
	 * @access public
	 * @return	
     */
    public function mustNotBeSignedIn() 
	{
        if (empty($this->userData)) 
		{
            $this->getUserData();
        }
		if (!empty($this->userData)) 
		{
			$this->redirect('/');
		}
    }

	/**
	 * Must be the owner of a group
	 *
	 * @access public
	 * @param array $group
	 * @return boolean	
	 */
	public function mustBeOwner($group = array())
	{
		if (empty($group))
		{
			$this->show404();
		}
		if (!$this->Group->isOwner($this->userData['id'], $group['owner_id'])) 
		{
			$this->show404();
		}
		return true;
	}
	

	/**
	 * Does a key exist in post data?
	 *  
	 * @param object $key[optional]
	 * @return boolean
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
	* @access public
	* @param integer $length
	* @return string 
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
     * @access public
     * @param mixed $url A string or array-based URL pointing to another location within the app, or an absolute URL
	 * @return
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
	 * @access public
	 * @param array $models
	 * @return
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