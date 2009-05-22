<?php
/**
 * Class for users controller
 */
class Users extends App_Controller
{
  
	/**
	 * Update a password
	 *
	 * @access public
	 * @return 
	 */
	function change_password()
	{
		$this->mustBeSignedIn();
        $this->data['page_title'] = 'Update Password';
		if ($this->postData) 
		{
			if ($this->User->changePassword($this->userData['id'], $this->userData['password']))
			{
				$this->cookie->set('user', $this->User->modelData);
				$this->redirect('/home', 'Your password was updated.');
			} 
			else 
			{
				$this->setErrors(array('User'));
				$this->cookie->setFlash('There was an error updating your password. See below for more details.', 'error');
			}
		}
		$this->load->view('users/change_password', $this->data);
	}
 
	/**
	 * Confirm a friend request
	 *
	 * @access public
	 * @param string $username
	 * @return 
	 */
	function confirm($username = null)
	{
		$this->mustBeSignedIn();
		$this->checkId($username);
		if ($this->User->confirm($username, $this->userData)) 
		{
			$this->redirect('/home', $username . ' is now following your posts.');
		} 
		else 
		{
			$this->redirect('/home', 'There was problem adding this follower', 'error');
		}
	}

	/**
	 * Delete an account
	 *
	 * @access public
	 * @return 
	 */
	function delete()
	{
		$this->mustBeSignedIn();
		$key = $this->cookie->get('update_key');
		if ((empty($this->postData['update_key'])) ||  ($this->postData['update_key'] != $key))
		{
			$this->redirect('/home');
		}
		if ($this->User->deleteMe($this->userData)) 
		{
        	$this->cookie->remove('user');
			$this->redirect('/signin', 'Your account has been deleted.');
		} 
		else 
		{
			$this->redirect('/home', 'There was a problem deleting your account.');
		}		
	}
	
    /**
     * Follow a user
     *
     * @todo check if user is not yourself
     * @param string $username
     */
    function follow($username = null)
    {
        $this->mustBeSignedIn();
		$user = $this->User->getByUsername($username);
		if ($this->User->follow($user, $this->userData))
		{
			if ($user['locked']) 
			{
				try
				{
					$this->load->library('Mail');
					ob_start(); // since debugging is set to '2', let's make sure we don't send anything to the browser until we set headers for the actual view.
					$this->mail->send($user['email'], null, null, 'Welcome', 'http://' . $_SERVER['HTTP_HOST'] . '/friend_requests');
					ob_end_clean();	
				}
				catch(Exception $e)
				{
					$this->redirect('/' . $username, 'Caught exception: ',  $e->getMessage(), "\n");
				}
				$message = 'A confirmation request has been sent to ' . $user['username'] . ' for confirmation.';	
							
			} 
			else 
			{
				$message = 'You are now following ' . $user['username'] . '.';				
			}
			$this->redirect('/' . $username, $message);
		}
		else
		{
			show_404();
		}
    }
   
	/**
	 * Process a request for following
	 *
	 * @access public
	 * @param string $key
	 * @return 
	 */
	function friend_requests()
	{
		$this->mustBeSignedIn();
		$this->data['requests'] = $this->User->getFriendRequests($this->userData['friend_requests']);
        $this->load->view('users/friend_requests', $this->data);		
	}

	/**
	 * User's home page
	 * @return 
	 */
    function home($replyTo = null)
    {
        $this->mustBeSignedIn();
		
		$this->data['message'] ="";
		if ($replyTo) 
		{
			$message = $this->Message->getOne($replyTo);
			if (!empty($message['user_id'])) 
			{
				$user = $this->User->get($message['user_id']);
				if ($user) 
				{
					$this->data['reply_to'] = $message['id'];
					$this->data['reply_to_username'] = $user['username'];
					$this->data['message'] = '@' . $user['username'] . ' ';
				}
			}
			//$this->data['message'] = "@".$message['reply_to_username']." ";
		}
        $this->data['page_title'] = 'Home';
        $this->data['messages'] = $this->Message->getMany($this->userData['private']);
		$this->data['following'] = $this->userData['following'];
        $this->load->view('users/home', $this->data);
		
    }

	/**
	 * Update a user profile
	 *
	 * @access public
	 * @return 
	 */
	function settings()
	{
		$this->mustBeSignedIn();
		$this->getUserData();
        $this->data['page_title'] = 'Settings';
		$key = md5($this->randomString(5));
		$this->userData['update_key'] = $key;
		$this->cookie->set('update_key', $key);
		if ($this->postData) 
		{
			if ($this->User->updateProfile($this->userData['id'])) 
			{
				$this->redirect('/settings', 'Your profile was updated.');
			} 
			else 
			{
				$this->setErrors(array('User'));
				$this->cookie->setFlash('There was an error updating your profile. See below for more details.', 'error');
			}
		}
		else 
		{
			$this->setData($this->userData);
		}
		$this->load->view('users/settings', $this->data);
	}
 
 	/**
 	 * Set the threading preference for a user
 	 * 
 	 * @return 
 	 * @param object $setting
 	 * @param object $uri
 	 */
 	function threading($setting,$uri)
	{
		$this->mustBeSignedIn();
		if ($setting != ("enable" || "disable"))
			$this->redirect('/home');
		
		//$this->load->helper('Util');	
		$this->getUserData();
		$this->setData($this->userData);	
		$key = md5($this->randomString(5));
		$this->userData['update_key'] = $key;
		$this->cookie->set('update_key', $key);
		$data = $this->userData;
		//echo $this->util->base64_url_decode($uri);
		$data['threading'] = ($setting == "enable")?1:0;
		if ($this->User->updateThreading($this->userData['id'],$data['threading']))
		{
			//$this->cookie->setUser($data);
			$this->redirect($this->util->base64_url_decode($uri),"You have {$setting}d threading");
		}
		else
		{
			$this->redirect($this->util->base64_url_decode($uri),"Something went wrong! You have not {$setting}d threading");
		}
		
	}
 
    /**
     * Sign in a user
     * 
     * @return
     * @todo Move those load method calls to one place
     */
    function signin()
    {
        $this->layout = 'public';
		$this->data['page_title'] = 'Sign In';
        if ($this->postData)
        {
            $user = $this->User->signIn($this->postData);
            if (empty($user))
            {
				$this->cookie->setFlash('The username and password do not match any in our records.', 'error');
            }
			else 
			{
				$this->cookie->set('user', $user['id']);
                $this->redirect('/home');
			}
        }
        $this->load->view('users/signin', $this->data);
    }

    /**
     * Sign up a new user
     * 
     * @return
     * @todo Move those load method calls to one place
     */
    function signup($email = null, $key = null)
    {
		if ((!$email) || (!$key))
		{
			show_404();
		}
        $this->layout = 'public';
		$this->data['page_title'] = 'Sign Up';
		$email_decode = base64_decode($email);
		$this->load->model(array('Invite'));
		$this->load->database();
		$invite = $this->Invite->get($email_decode, $key);
		if (empty($invite)) 
		{
			show_404();
		}
		if ($invite['activated'] == 1) 
		{
			$this->redirect('/signin', 'This invite has already been activated.');
		}
        if ($this->postData)
        {
            if ($this->User->signUp($this->postData))
            {
				$this->Invite->accept($email_decode, $key);
				try
				{
					$this->load->library('Mail');
					ob_start(); // since debugging is set to '2', let's make sure we don't send anything to the browser until we set headers for the actual view.
					$this->mail->send($this->postData['email'], null, null, 'Welcome', 'Welcome to the microblog!');
					ob_end_clean();	
				}
				catch(Exception $e)
				{
					$this->redirect('/signin', 'Caught exception: ',  $e->getMessage(), "\n");
				}
                $this->redirect('/signin', 'Your account has been created. Please sign in.');
            }
			else 
			{
				$this->setErrors(array('User'));
                $this->cookie->setFlash('There was an error signing up. Please see below for details.', 'error');
			}
        }
		else 
		{
			$this->data['email'] = $invite['email'];
		}
		$this->data['invite_email'] = $email;
		$this->data['invite_key'] = $key;		
        $this->load->view('users/signup', $this->data);
    }
   
    /**
     * Sign out a user
     * 
     * @return
     */
    function signout()
    {
        $this->cookie->remove('user');
        $this->redirect('/signin', 'You have successfully signed out.');
    }

    /**
     * unFollow a user
     *
     * @todo check if user is not yourself
     * @param string $username
     */
    function unfollow($username = null)
    {
        $this->mustBeSignedIn();
		if ($this->User->unfollow($username, $this->userData))
		{
			$this->redirect('/' . $username);
		}
		else
		{
			show_404();
		}
    } 

    /**
     * View a users public page
     *
     * @param string $username
     * @return
     */
    function view($username = null)
    {	
		$this->getUserData();
       	$user = $this->User->getByUsername($username);
		$this->sidebar = "users/userprofile";
        if ($user)
        {
            $this->getUserData();
            $this->data['page_title'] = $username;
            $this->data['username'] = $username;
            $this->data['messages'] = $this->Message->getMany($user['public']);
			$this->data['friend_status'] = $this->User->getFriendStatus($user, $this->userData);
            $this->load->view('users/view', $this->data);
        }
        else
        {
            show_404();
        }
    }
   
}
?>