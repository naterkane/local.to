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
 * Users
 * 
 * @package 	Nomcat
 * @subpackage	nomcat-controllers
 * @category	controller
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Users extends App_Controller
{
	/**
	 * Avatar management
	 *
	 * @access public
	 * @return 
	 */
	public function avatar()
	{
		$this->mustBeSignedIn();
		$this->data['user'] = $this->userData;
        $this->data['page_title'] = 'Upload Avatar';
		$this->data['avatarid'] = $this->userData['id'];
		$this->data['avatartype'] = 'User';
		$this->_avatar($this->userData['id'], $this->userData['username']);
	}
	
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
				$this->load->library(array('Mail'));
				$this->mail->sendResetPassword($this->userData['email']);				
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
		$redirect = $this->getRedirect();				
		if ($this->User->confirm($username, $this->userData)) 
		{
			$this->redirect($redirect, $username . ' is now following your posts.');
		} 
		else 
		{
			$this->redirect($redirect, 'There was problem adding this follower', 'error');
		}
	}

	function delete_account()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = 'Delete your Account';
		$key = md5($this->randomString(5));
		$this->userData['update_key'] = $key;
		$this->cookie->set('update_key', $key);
		$this->load->view('users/delete_account', $this->data);
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
			$this->mail->sendDeletion($this->userData['email']);			
        	$this->cookie->remove('user');
			$this->redirect('/signin', 'Your account has been deleted.');
		} 
		else 
		{
			$this->redirect('/home', 'There was a problem deleting your account.', 'error');
		}		
	}

	/**
	 * Deny a friend request
	 *
	 * @access public
	 * @param string $username
	 * @return 
	 */
	function deny($username = null)
	{
		$this->mustBeSignedIn();
		$this->checkId($username);
		$redirect = $this->getRedirect();		
		if ($this->User->deny($username, $this->userData)) 
		{
			$this->redirect($redirect, 'User request denied.');
		} 
		else 
		{
			$this->redirect($redirect, 'There was problem updating your friend requests', 'error');
		}
	}

	/**
	 * Favorites
	 *
	 * @access public
	 * @return null
	 */
	public function favorites($username = null)
	{
		if (!$username) 
		{
			$this->mustBeSignedIn();
			$user = $this->userData;
			$this->data['User']['threading'] = false;
		}
		else 
		{
			$user = $this->User->getByUsername($username);			
		}
		$this->data['page_title'] = 'Favorites';
        $this->data['messages'] = Page::make('Message', $user['favorites']);
		$this->load->view('users/favorites', $this->data);
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
				$this->mail->sendFriendRequest($user['email'], $this->userData['email'], 'Welcome', $this->config->item('base_url') . 'friend_requests');
				$message = 'A confirmation request has been sent to ' . $user['username'] . '.';								
			} 
			else 
			{
				$message = 'You are now following ' . $user['username'] . '.';				
			}
			$this->redirect('/' . $username, $message);
		}
		else
		{
			$this->show404();
		}
    }
   
	/**
	 * Show all users user is following
	 *
	 * @access public
	 * @return 
	 */
	public function following($username = null)
	{
        if (empty($username)) $this->mustBeSignedIn();
		
		if ($username != null)
		{
			$user = $this->User->getByUsername($username);
		}
		else 
		{
			$user = $this->userData;
		}
		$this->data['user'] = $user;
		$this->data['page_title'] = 'people '.$user['username'].' is following';
		$this->data['users'] = Page::make('User', $user['following']);
		$this->load->view('users/viewlist', $this->data);
	}
	
	/**
	 * Show all users user is following
	 *
	 * @access public
	 * @return 
	 */
	public function followers($username = null)
	{
        if (empty($username)) $this->mustBeSignedIn();
		
		if ($username != null)
		{
			$user = $this->User->getByUsername($username);
			
		}
		else 
		{
			$user = $this->userData;
		}
		$this->data['user'] = $user;
		$this->data['page_title'] = 'people following '.$user['username'];
		$this->data['users'] = Page::make('User', $user['followers']);		
		$this->load->view('users/viewlist', $this->data);		
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
		$this->data['message'] = null;
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
		}
        $this->data['page_title'] = 'Home';
		$this->data['next'] = null;
        $this->data['messages'] = Page::make('Message', $this->userData['private']);
		$this->data['following'] = $this->userData['following'];
        $this->load->view('users/home', $this->data);
		
    }

	/**
	 * Mentions
	 *
	 * @access public
	 * @return null
	 */
	public function mentions()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = 'Mentions';	
		$this->data['User']['threading'] = false;				
        $this->data['messages'] = Page::make('Message', $this->userData['mentions']);
		$this->load->view('users/mentions', $this->data);
	}

	/**
	 * Mentions
	 *
	 * @access public
	 * @return null
	 */
	public function profile($username = null)
	{
		$this->checkId($username);		
		$this->sidebar = "users/userprofile";
		$this->data['profile'] = $this->User->getByUsername($username);	
		
		if (empty($this->data['profile'])) 
		{
			$this->show404();
		}		
		$this->data['username'] = $this->data['profile']['username'];	
		$this->data['page_title'] = $username;
		$this->load->view('users/profile', $this->data);
	}
	
	/**
	 * Recover password
	 *
	 * @access public
	 * @return 
	 */
	public function recover_password()
	{
		if (!empty($this->postData['email']))
		{
			$user = $this->User->getByEmail($this->postData['email']);
			if ($user) 
			{
				$this->load->model(array('Email_key'));
				$key = $this->randomString(10);			
				$data = $this->Email_key->create();
				$data['id'] = md5($key);
				$data['id_unhashed'] = $key;
				$data['user_id'] = $user['id'];		
				$this->Email_key->save($data);
				$this->load->library('Mail');			
				$this->mail->sendRecoverPassword($user['email'],  $this->config->item('base_url') . 'reset_password/' . $data['id_unhashed']);
				$this->redirect('/recover_password', 'An email has been sent to ' . $user['email'] . ' with instructions on how to reset your password.', 'error');
			}
			else 
			{
				$this->redirect('/recover_password', 'Email not found.', 'error');
			}
		}
		$this->load->view('users/recover_password');
	}
	
	/**
	 * Reset password
	 *
	 * @access public
	 * @param string $key
	 * @return 
	 */
	public function reset_password($key = null)
	{
		if (!$key) 
		{
			$this->redirect('/', "Sorry, we can't reset your password at this time.");
		}
		else 
		{
			$this->load->model(array('Email_key'));
			$data = $this->Email_key->find(md5($key));
			$user = $this->User->get($data['user_id']);
			if (empty($data) || empty($user)) 
			{
				$this->show404();
			} 
			else 
			{
				if ($this->postData) 
				{
					if ($this->User->resetPassword($user, $this->postData)) 
					{
						$this->Email_key->delete(md5($key));
						$this->load->library(array('Mail'));
						$this->mail->sendResetPassword($user['email']);
						$this->redirect('/signin', 'Your password has been updated. Please sign in.');
					} 
					else 
					{
						$this->cookie->setFlash('There was an error updating your password. Please see below for details');
					}
				}
				$this->load->view('users/reset_password');
			}
		}
	}
	
	/**
	 * RSS feed of user's messages
	 * @param string $username
	 * @return null
	 */
	public function rss($username = null)
	{
       	$user = $this->User->getByUsername($username);
        if ($user)
        {
			$this->layout = 'rss';	
			$this->data['user'] = $user;
			$this->data['page_title'] = $username;	
        	$this->data['messages'] = Page::make('Message', $user['public']);
			
            $this->load->view('users/view.rss.php', $this->data);
        }
        else
        {
            $this->show404();
        }
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
     * Sign in a user
     * 
     * @return
     * @todo Move those load method calls to one place
     */
    function signin()
    {
        $this->layout = 'public';
		$this->data['page_title'] = 'Sign In';
		$this->data['redirect'] = $this->getRedirect();	
		$this->load->model("message");
       	$pt = $this->Message->getTimeline();
        $this->data['messages'] = Page::make('Message', $pt);
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
                $this->redirect($this->data['redirect']);
			}
        }
        $this->load->view('users/signin', $this->data);
    }

    /**
     * Sign out a user
     * 
     * @return
     */
    public function signout()
    {
        $this->cookie->remove('user');
        $this->redirect('/signin', 'You have successfully signed out.');
    }

    /**
     * Sign up a new user
     * 
     * @return
     * @todo Move those load method calls to one place
     */
    function signup($key = null)
    {
		if (!$key)
		{
			$this->redirect('/request_invite', "Sorry, you must request an invitation before you can sign up.");
		}
        $this->layout = 'public';
		$this->data['page_title'] = 'Sign Up';
		$this->load->model(array('Invite','message'));
       	$pt = $this->Message->getTimeline();
        $this->data['messages'] = Page::make('Message', $pt);
		$this->load->database();
		$invite = $this->Invite->get($key);
		if (empty($invite)) 
		{
			$this->show404();
		}
		if ($invite['activated'] == 1) 
		{
			$this->redirect('/signin', 'This invite has already been activated.');
		}		
        if ($this->postData)
        {
			$this->postData['key'] = $key;	
            if ($this->User->signUp($this->postData, $invite['permission']))
            {
				$this->Invite->accept($key);
				$this->mail->sendWelcome($this->postData['email']);
				$user = $this->User->getByUsername($this->postData['username']);
				$this->cookie->set('user', $user['id']);
                $this->redirect('/settings', 'Your account has been created.');
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
		$this->data['invite_key'] = $invite['key'];		
        $this->load->view('users/signup', $this->data);
    }

	public function sms()
	{
		$this->mustBeSignedIn();
		$this->load->model(array('Sms_key'));		
		$this->User->Sms_key = $this->Sms_key;
		$this->data['sms_pending'] = $this->User->smsPending($this->userData);				
		if (isset($this->params['cancel']) && $this->data['sms_pending']) 
		{
			$this->Sms_key->delete($this->userData['id']);
			$this->userData['phone'] = null;
			$this->userData['carrier'] = null;
			$this->userData['sms_activated'] = false;
			$this->userData['device_updates'] = false;			
			$this->User->save($this->userData);
			$this->redirect('/settings/sms', 'Your sms activation has been canceled.');
		}
        $this->data['page_title'] = 'Settings / SMS';		
		$this->load->library(array('Mail'));
		$this->data['carriers'] = $this->mail->carriers;
		if ($this->postData)
		{
			if ($this->postDataKey('key')) 
			{
				if ($this->User->smsKey($this->postData['key'], $this->userData)) 
				{
					$this->redirect('/settings/sms', 'Your sms number has been activated');
				} 
				else 
				{
					$this->cookie->setFlash('The sms code is incorrect.', 'error');
				}
			}
			else 
			{
				if ($this->User->updateSms($this->userData))
				{	
					$this->mail->sms($this->postData['phone'] . $this->postData['carrier'], null, $this->User->Sms_key->code, true, true);
					$this->redirect('/settings/sms', 'Your sms settings were updated');
				}
				else 
				{
					$this->setErrors(array('User'));
					$this->cookie->setFlash('There was an error updating your sms settings. See below for more details.', 'error');
				}
			}
		}
		else 
		{
			$this->setData($this->userData);
		}
		$this->load->view('users/sms', $this->data);
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
		{
			$this->redirect('/home');			
		}
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
			$this->show404();
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
       	$user = $this->User->getByUsername($username);
		$this->sidebar = "users/userprofile";
        if ($user)
        {
			$this->data['rss_updates'] = true;
            $this->data['page_title'] = $username;
            $this->data['username'] = $username;
        	$this->data['messages'] = Page::make('Message', $user['public']);
			$this->data['friend_status'] = $this->User->getFriendStatus($user, $this->userData);
            $this->load->view('users/view', $this->data);
        }
        else
        {
            $this->show404();
        }
    }

}
?>