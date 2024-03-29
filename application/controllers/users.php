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
	 */
	public function avatar()
	{
		$this->mustBeSignedIn();
		$this->data['user'] = $this->userData;
        $this->data['page_title'] = 'Upload Avatar';
		$this->data['avatarid'] = $this->userData['id'];
		$this->data['avatartype'] = 'User';
		$this->_avatar($this->userData['id'], $this->userData['id']);
	}
	
	/**
	 * Update a password
	 *
	 * @access public
	 */
	public function change_password()
	{
		$this->mustBeSignedIn();
        $this->data['page_title'] = 'Update Password';
		if ($this->postData) 
		{
			if ($this->User->changePassword($this->userData['id'], $this->userData['password']))
			{
				$this->load->library(array('Mail'));
				$this->mail->sendResetPassword($this->userData);				
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
	 */
	public function confirm($username = null)
	{
		$this->mustBeSignedIn();
		$this->checkId($username);
		$redirect = $this->getRedirect();				
		if ($this->User->confirm($username, $this->userData)) 
		{
			$user = $this->User->getByUsername($username);
			$this->mail->sendRequestConfirmation($user, $this->userData);			
			$this->redirect($redirect, $username . ' is now following your updates.');
		} 
		else 
		{
			$this->redirect($redirect, 'There was problem adding this follower', 'error');
		}
	}

	/**
	 * Used for if a user wishes to delete her own account
	 * If confirmed, the user will be passed to Users::delete()
	 * @access public
	 */
	public function delete_account()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = 'Delete your Account';
		$key = md5($this->randomString(5));
		$this->userData['update_key'] = $key;
		$this->setData($this->userData);		
		$this->cookie->set('update_key', $key);
		$this->load->view('users/delete_account', $this->data);
	}

	/**
	 * Delete an account
	 * Can not be accessed directly, must be passed from Users::delete_account()
	 * @access public
	 */
	public function delete()
	{
		$this->mustBeSignedIn();
		$this->load->model("group");
		$key = $this->cookie->get('update_key');
		$data = $this->userData;
		if ((empty($this->postData['update_key'])) ||  ($this->postData['update_key'] != $key))
		{
			$this->redirect('/home');
		}
		try {
			$this->User->deleteMe($data);
			$this->mail->sendDeletion($data);		
        		$this->cookie->remove('user');
		} 
		catch (Exception $e) 
		{
			$this->redirect('/home', "There was a problem deleting your account.<br/>\n".$e, 'error');
		}
		$this->redirect('/signin', 'Your account has been deleted.');		
	}

	/**
	 * Deny a friend request
	 *
	 * @access public
	 * @param string $username
	 */
	public function deny($username = null)
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
	 * View a user's favorite messages
	 *
	 * @access public
	 * @param string $username
	 */
	public function favorites($username = null)
	{
		if (!$username) 
		{
			$this->mustBeSignedIn();
			$this->data['user'] = $this->userData;
			//$this->data['User']['threading'] = false;
		}
		else 
		{
			$this->data['profile'] = $this->User->getByUsername($username);			
			$this->data['user'] = $this->data['profile'];
			$this->isProfile = true;									
		}
		$this->data['page_title'] = 'Favorites';
        $this->data['messages'] = Page::make('Message', $this->data['user']['favorites'],array('threading'=>false));
		$this->load->view('users/favorites', $this->data);
	}
	
    /**
     * Follow a user
     * 
     * @todo fix redirect issue that destroys session
     * @param string $username
     * @access public
     */
	public function follow($username = null)
    {
        $this->mustBeSignedIn();
		$user = $this->User->getByUsername($username);
		$redirect = $this->getRedirect();
		$type = null;
		if ($this->User->follow($user, $this->userData))
		{
			if ($user['locked']) 
			{
				$this->mail->sendFriendRequest($user, $this->userData, $this->config->item('base_url') . 'friend_requests');
				$message = 'A confirmation request has been sent to ' . $user['username'] . '.';								
			} 
			else 
			{
				$this->mail->sendFollowingConfirmation($user, $this->userData);
				$message = 'You are now following ' . $user['username'] . '.';		
				$type = "success";		
			}
			$this->redirect($redirect, $message, $type);
		}
		else
		{
			$message = "Oh my! We had trouble finding the user you're trying to follow. We don't know anyone called ". $user['username'];
			$type = "error";
			$this->redirect($redirect,$message,$type);
		}
    }

    /**
     * Unfollow a user
     *
     * @param string $username
     * @access public
     */
    function unfollow($username = null)
    {
        $this->mustBeSignedIn();
		$redirect = $this->getRedirect();
		$user = $this->User->getByUsername($username);
		$username = $user['username'];
		$action = $this->User->unfollow($username, $this->userData);
		$this->redirect($redirect,$action['message'],$action['type']);
    } 
   
	/**
	 * Show all users user is following
	 * If username is empty, show current user's list.
	 *
	 * @param string $username
	 * @access public
	 */
	public function following($username = null)
	{
		if (!$username) 
		{
			$this->mustBeSignedIn();
			$this->data['user'] = $this->userData;
		}
		else 
		{
			$this->data['profile'] = $this->User->getByUsername($username);			
			$this->data['user'] = $this->data['profile'];
			$this->isProfile = true;									
		}
		$this->data['page_title'] = 'People ' . ucwords($this->data['user']['username']) . ' is Following';
		$this->data['users'] = Page::make('User', $this->data['user']['following']);		
		$this->load->view('users/viewlist', $this->data);
	}
	
	/**
	 * Show all users user is following
	 * If username is empty, show current user's list.
	 *
	 * @param string $username
	 * @access public
	 */
	public function followers($username = null)
	{
		if (!$username) 
		{
			$this->mustBeSignedIn();
			$this->data['user'] = $this->userData;
		}
		else 
		{
			$this->data['profile'] = $this->User->getByUsername($username);			
			$this->data['user'] = $this->data['profile'];
			$this->isProfile = true;									
		}
		$this->data['page_title'] = 'People Following '.ucwords($this->data['user']['realname']);
		$this->data['users'] = Page::make('User', $this->data['user']['followers']);		
		$this->load->view('users/viewlist', $this->data);		
	}

	/**
	 * Show list of all friend requests
	 *
	 * @access public
	 */
	public function friend_requests()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = 'Friend Requests';
		$this->data['requests'] = $this->User->getFriendRequests($this->userData['friend_requests']);
        $this->load->view('users/friend_requests', $this->data);		
	}

	/**
	 * User's home page
	 * 
	 * @access public
	 * @param int $replyTo Id of a message user is replying to. If ID is not blank, message sent from from this page will be a reply
	 */
    public function home($replyTo = null)
    {
        $this->mustBeSignedIn();
		$this->data['message'] = null;
		$this->setReplyTo($replyTo);
        $this->data['page_title'] = 'Home';
		$this->data['next'] = null;
		if ($this->userData['threading']) 
		{
			$messages = $this->userData['private_threaded'];
		} 
		else 
		{
			$messages = $this->userData['private'];
		}
        $this->data['messages'] = Page::make('Message', $messages);
		$this->data['following'] = $this->userData['following'];
		$this->data['redirect'] = $this->getRedirect();
		$this->User->updateRead('private', $this->data['profile']);		
        $this->load->view('users/home', $this->data);
		
    }

	/**
	 * 
	 * @param string $view [optional]
	 * @return void
	 */
	public function get_unread($view,$group = null){
		if (!$this->userData){
			echo json_encode(array('redirect'=>$this->config->item('base_url') . "signin"));
			exit;
		}
		if (!empty($group)){
			$group = $this->Group->getByName($group);
		}
		$key = "";
		$am = null;
		switch($view){
			case"home":
				$key = "private";
				$am = $this->userData[$key];
				if ($this->userData['threading']) 
				{
					$am = $this->userData[$key.'_threaded'];
				}
				break; 
			case"replies":
				$key = "mentions";
				$am = $this->userData[$key];
				break;
			case"inbox":
				$key = "inbox";
				$am = $this->userData[$key];
				break;
			default:
				$key = $view;
		} 

		$this->data['message'] = null;
		if (!empty($group)):
			$am = $group['messages'];
			$unreadcount = $this->User->getUnreadGroup($this->userData,$group);
		else:
			$unreadcount = $this->User->getUnread($key, $this->userData);
		endif;
		
		$m = array();
		//echo "<pre>";
		//echo" unread count " . $unreadcount;
		//var_dump($am);
		while ($unreadcount > 0){
		
			array_push($m,$this->Message->getOne(array_shift($am)));
			$c = count($m)-1;
			//echo $c . "\n";
			$m[$c]['username'] = $m[$c]['User']['username'];
			$m[$c]['user_id'] = $m[$c]['User']['id'];
			$m[$c]['realname'] = $m[$c]['User']['realname'];
			unset($m[$c]['User']);
			$unreadcount--;
		}
		//var_dump($m);
		header('Content-Type: application/json; charset=utf-8'); 
		echo json_encode($m);
		exit;
		//$this->data['messages'] = Page::make('Message', $messages);
		//$this->data['following'] = $this->userData['following'];
		//$this->data['redirect'] = $this->getRedirect();
		//$this->User->updateRead('private', $this->data['profile']);		
        //$this->load->view('messages/messages_json', $this->data);
		//echo
	}

	/**
	 * Show user mentions
	 *
	 * @access public
	 */
	public function mentions()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = 'Mentions';				
        $this->data['messages'] = Page::make('Message', $this->userData['mentions'],array('threading'=>false));
		$this->User->updateRead('mentions', $this->data['profile']);
		$this->load->view('users/mentions', $this->data);
	}
	
	/**
	 * Recover password
	 *
	 * @access public
	 */
	public function recover_password()
	{
		$this->layout = 'public';
		$this->data['page_title'] = "Recover Password";
		if (!empty($this->postData['email']))
		{
			$user = $this->User->getByEmail($this->postData['email']);
			if (!$user) {
				$this->redirect('/recover_password', 'Email not found.', 'error');
			}
			$this->load->model(array('Email_key'));
			$key = $this->randomString(10);			
			$data = $this->Email_key->create();
			$data['id'] = md5($key);
			$data['id_unhashed'] = $key;
			$data['user_id'] = $user['id'];		
			$this->Email_key->save($data);
			$this->load->library('Mail');			
			$this->mail->sendRecoverPassword($user,  $this->config->item('base_url') . 'reset_password/' . $data['id_unhashed']);
			$this->redirect('/recover_password', 'An email has been sent to ' . $user['email'] . ' with instructions on how to reset your password.', 'neutral');
		}
		$this->load->view('users/recover_password');
	}
	
	/**
	 * Reset password
	 *
	 * @access public
	 * @param string $key
	 */
	public function reset_password($key = null)
	{
		$this->layout = "public";
		$this->data['page_title'] = "Reset Password";
		if (!$key) 
		{
			$this->redirect('/', "Sorry, we can't reset your password at this time.","error");
		}
		else 
		{
			$this->load->model(array('Email_key'));
			$data = $this->Email_key->find(md5($key));
			$user = $this->User->get($data['user_id']);
			if (empty($data) || empty($user)) 
			{
				$this->redirect('/');
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
						$this->redirect('/signin', 'Your password has been updated. Please sign in.',"success");
					} 
					else 
					{
						$this->cookie->setFlash('There was an error updating your password. Please see below for details',"error");
					}
				}
				$this->load->view('users/reset_password');
			}
		}
	}
	
	/**
	 * RSS feed of user's messages
	 *
	 * @param string $username
	 * @access public
	 * @return void
	 */
	public function rss($username = null)
	{
       	$user = $this->User->getByUsername($username);
        if (!empty($user))
        {
        		if ($user['locked']){
        			print "This user's privacy settings prevent you from viewing their updates";
				      exit;
        		}
						$this->layout = 'rss';	
						$this->data['user'] = $user;
						$this->data['page_title'] = $user['username'];	
		        $this->data['messages'] = Page::make('Message', $user['public']);
		        $this->load->view('users/view.rss.php', $this->data);
				}
        else
        {
            print "Sorry we couldn't find the user you're looking for.";
			      exit;
		    }
	}

	/**
	 * Update a user profile
	 *
	 * @access	public
	 * @return	void
	 */
	public function settings()
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
				$this->redirect('/settings', 'Your profile was updated.',"success");
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
	 * Manage linked services
	 * 
	 * @access public
	 * @return void
	 */
	public function accounts()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = 'Linked Accounts';
		$saveUserData = false;
		if ($this->postData) {
			if (!empty($this->postData['gowalla_user']) && !empty($this->postData['gowalla_pass'])){
		  $this->userData['gowalla_user'] = $this->postData['gowalla_user'];
		  $this->userData['gowalla_pass'] = $this->postData['gowalla_pass'];
		  $saveUserData = true;
			}
		
			
		}
    /**
     * foursquare
     */
    //Put in the key and secret for your Foursquare app
    //Your values will be different than mine
    $foursquare_consumer_key = $this->config->item('foursquare_consumer_key');
    $foursquare_consumer_secret = $this->config->item('foursquare_consumer_secret');

    //Includes the foursquare-asyc library files
    require_once(APPPATH.'libraries/foursquare/EpiCurl.php');
    require_once(APPPATH.'libraries/foursquare/EpiOAuth.php');
    require_once(APPPATH.'libraries/foursquare/EpiFoursquare.php');
    $foursquareObj = new EpiFoursquare($foursquare_consumer_key, $foursquare_consumer_secret);
    try {
      $results = $foursquareObj->getAuthorizeUrl();
      $this->data['foursquare_loginurl'] = $results['url'] . "?oauth_token=" . $results['oauth_token'];
    } catch (Execption $e) {
      echo "Error: " . $e;//If there is a problem throw an exception
    } 
      
    //echo '$results[\'oauth_token_secret\'] '. $results['oauth_token_secret'].'<br>';
    //echo '$this->userData[\'foursquare_secret\'] '. $this->userData['foursquare_secret'] . '<br>';
    if (empty($this->userData['foursquare_secret']) || empty($this->userData['foursquare_token']) && empty($this->userData['foursquare_token_secret'])){
	    
      //$_SESSION['secret'] = $results['oauth_token_secret'];
      //echo $_SESSION['secret'] . "<br/>";
      $this->userData['foursquare_secret'] = $results['oauth_token_secret'];   
      $saveUserData = true;
      //echo '$results["oauth_token"]: ';var_dump($results['oauth_token']);echo "<br>";
      //$this->setData($this->userData);  
      //$this->User->save($this->userData, array('ignoreModelFields'=>true));
      //echo $_SESSION['secret'];
			
		}
		if(!empty($this->userData['foursquare_token']) && !empty($this->userData['foursquare_token_secret'])){
		  try {
	      //Making a call to the API
	      
	      $foursquareObj->setToken($this->userData['foursquare_token'], $this->userData['foursquare_token_secret']);
	      $foursquareTest = $foursquareObj->get_user();
	      //print_r($foursquareTest->response);
	      $this->data['foursquare_response'] = $foursquareTest->response;
	          // Save foursquare response data
		    $this->userData['foursquare_userdata'] = $foursquareTest->response;
		    $saveUserData = true;
		    //$this->User->save($this->userData, array('ignoreModelFields'=>true)); 
		  } catch (Exception $e) {
        echo "Error: " . $e;
      }
		}
		//$this->userData['update_key'] = $key;
    //$this->cookie->set('update_key', $key);
    //var_dump($this->data);
    
    // lets finally save that userdata
    if ($saveUserData == true){
      $this->User->save($this->userData, array('ignoreModelFields'=>true));
    }  
    $this->data['user'] = $this->userData;
		$this->load->view('users/accounts', $this->data);
	}
	/**
   * Callback for foursquare user authentication
   * 
   * @access public
   * @return void
   */
  public function foursquare_callback()
  {
    $this->mustBeSignedIn();
  	 // foursquare
    //echo '$_REQUEST[\'oauth_token\'] '. $_REQUEST['oauth_token']."<br>";
    //Put in the key and secret for your Foursquare app
    //Your values will be different than mine
    $foursquare_consumer_key = $this->config->item('foursquare_consumer_key');
    $foursquare_consumer_secret = $this->config->item('foursquare_consumer_secret');
    //session_start();
    //Includes the foursquare-asyc library files
    require_once(APPPATH.'libraries/foursquare/EpiCurl.php');
    require_once(APPPATH.'libraries/foursquare/EpiOAuth.php');
    require_once(APPPATH.'libraries/foursquare/EpiFoursquare.php');
    $foursquareObj = new EpiFoursquare($foursquare_consumer_key, $foursquare_consumer_secret);
    $foursquareObj->setToken($_REQUEST['oauth_token'],$this->userData['foursquare_secret']);
    $token = $foursquareObj->getAccessToken();
    $foursquareObj->setToken($token->oauth_token, $token->oauth_token_secret);
    //echo "<pre>";
    //var_dump($token);
    //if (isset($token->oauth_token) && $foursquareObj->setToken($token->oauth_token, $token->oauth_token_secret)){
		  try {
		   //Making a call to the API
		   $foursquareTest = $foursquareObj->get_user();
		   $this->data['foursquare_response'] = $foursquareTest->response;
		   print_r($foursquareTest->response);
			} catch (Exception $e) {
			  echo "Error: " . $e;
			}
	    $this->data['foursquare_token'] = $token->oauth_token;  
	    $this->userData['foursquare_secret'] = null;
	    $this->userData['foursquare_token'] = $token->oauth_token;
	    $this->userData['foursquare_token_secret'] = $token->oauth_token_secret;
	    $this->User->save($this->userData, array('ignoreModelFields'=>true));
    //}
    //$this->load->view('users/accounts', $this->data);
    $this->redirect('/settings/accounts');
  }
	/**
     * Sign in a user
     * 
	   * @access public
     */
    public function signin()
    {
        $this->layout = 'public';
				$this->data['page_title'] = 'Sign In';
				$this->data['redirect'] = $this->getRedirect();	
				//echo $this->data['redirect'];
				if ($this->userData) {
					$this->redirect($this->getRedirect("true"));
				}
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
							//var_dump($this->cookie->set('user', $user['id']));
							//exit;
			        $this->redirect($this->data['redirect']);
						}
        }
        $this->load->view('users/signin', $this->data);
    }

    /**
     * Sign out a user
     * 
	   * @access public 
     */
    public function signout()
    {
        $this->cookie->remove('user');
        $this->redirect('/signin', 'You have successfully signed out.',"success");
    }

    /**
     * Sign up a new user
     * Key is required for signup. 
	 *
	 * @param string $key
	 * @access public
     */
	public function signup($key = null)
    {
		$this->mustNotBeSignedIn();
		if (!$key)
		{
			$this->redirect('/request_invite', "Sorry, you must request an invitation before you can sign up.","error");
		}
        $this->layout = 'public';
		$this->data['page_title'] = 'Sign Up';
		$this->load->model(array('Invite','message'));
		$this->load->database();
		$invite = $this->Invite->get($key);
		if (empty($invite)) 
		{
			$this->redirect('/request_invite', "Sorry, your invitation is invalid. You must request a new invitation before you can sign up.","error");
		}
		if ($invite['activated'] == 1) 
		{
			$this->redirect('/signin', 'This invite has already been activated.',"error");
		}		
        if ($this->postData)
        {
			$this->postData['key'] = $key;	
            if ($this->User->signUp($this->postData, $invite['permission']))
            {
				$this->Invite->accept($key);
				$this->mail->sendWelcome($this->postData);
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

    /**
     * Change SMS/Notification settings
	 *
	 * @access public
     */
	public function notifications()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = "Notification Settings";
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
			$this->redirect('/settings/notifications', 'Your sms activation has been canceled.','neutral');
		}
        $this->data['page_title'] = 'Settings / Notifications';		
		$this->load->library(array('Mail'));
		$this->data['carriers'] = $this->mail->carriers;
		if ($this->postData)
		{
			if ($this->postDataKey('key')) 
			{
				if ($this->User->smsKey($this->postData['key'], $this->userData)) 
				{
					$this->redirect('/settings/notifications', 'Your sms number has been activated',"success");
				} 
				else 
				{
					$this->cookie->setFlash('The SMS code you entered doesn\'t match what we sent you. Please try again.', 'error');
				}
				
			}
			else 
			{
				if ($this->User->updateSms($this->userData))
				{	
					$this->mail->sms($this->postData['phone'] . $this->postData['carrier'], null, 'Secret Key: ' . $this->User->Sms_key->code, true, true);
					$this->cookie->setFlash('Your sms settings were updated','success');
				}
				else 
				{
					$this->setErrors(array('User'));
					$this->cookie->setFlash('There was an error updating your sms settings. See below for more details.', 'error');
				}
			}
			if ($this->User->updateProfile($this->userData['id'])) 
			{
				$this->cookie->setFlash('Your notification settings have been updated.',"success");
			} 
			else 
			{
				$this->setErrors(array('User'));
				$this->cookie->setFlash('There was an error updating your profile. See below for more details.', 'error');
			}
			$this->redirect('/settings/notifications');
		}
		else 
		{
			
			$this->setData($this->userData);
		}
		$this->data['key'] = null;
		$this->load->view('users/notifications', $this->data);
	}

 	/**
 	 * Set the threading preference for a user
 	 * 
 	 * @param string $setting Either 'enabled' or 'disabled'
 	 * @todo Make $setting boolean
 	 * @access public
 	 */
 	public function threading($setting)
	{
		$this->mustBeSignedIn();
		if ($setting != ('enable' || 'disable'))
		{
			$this->show404();
		}
		else 
		{
			$type = null;
			$redirect = $this->getRedirect();
			if ($setting == 'enable') 
			{
				$this->userData['threading'] = true;
			}
			else 
			{
				$this->userData['threading'] = false;				
			}
			if ($this->User->save($this->userData))
			{
				$message = "You have {$setting}d threading";
				$type = "success";
			}
			else
			{
				$message = "Something went wrong! You have not {$setting}d threading";
				$type = "error";
			}
			$this->redirect($redirect, $message, $type);			
		}
	}

    /**
     * View a users public page
     *
	 * @access public
     * @param string $username
     */
	public function view($username = null)
    {	
       	$user = $this->User->getByUsername($username);
		$this->sidebar = "users/userprofile";
        	if (!$user){
			$this->show404();
		}
		$this->data['page_title'] = ucwords($user['realname']);		
		$this->load->loadHelper('Html');
		$this->data['page_title'] = $this->load->passData['html']->name($user);
		$this->data['view_user'] = $user;
		$this->data['username'] = $user['username'];
		$this->data['profile'] = $user;	
		$this->isProfile = true;
		$this->data['isLocked'] = $this->User->isLocked($user, $this->userData);
		$this->data['friend_status'] = $this->User->getFriendStatus($user, $this->userData);			
		if (!$this->data['isLocked']) 
		{
			$this->Message->threaded = false; //force threading
        	$this->data['messages'] = Page::make('Message', $user['public']);
		}
		//if a user is locked, don't show rss. Doesn't matter what the relationship is
		$this->data['rss_updates'] = !$user['locked'];
		$this->load->view('users/view', $this->data);			
    }

	/**
	 * Show user's public profile
	 *
	 * @param string $username
	 * @access public
	 */
	public function profile($username = null)
	{
		$this->checkId($username);
		$user = $this->User->getByUsername($username);
		if (!$user){
			$this->redirect("/");
		}
		$this->data['page_title'] = ucwords($user['realname']);		
		$this->sidebar = "users/userprofile";
		$this->data['user'] = $user;
		$this->data['profile'] = $user;		
		$this->isProfile = true;
		if (empty($this->data['user'])) 
		{
			$this->show404();
		}		
		$this->data['username'] = $this->data['user']['username'];	
		$this->data['page_title'] = $this->data['user']['username'];
		$this->data['isLocked'] = ($this->userData['id'] == $this->data['user']['id']) ? false : ($this->data['user']['locked'] && !$this->User->isFollowing($this->userData['id'],$this->data['user']['followers']));
		$this->data['rss_updates'] = !$this->data['isLocked'];
		$this->load->view('users/profile', $this->data);
	}

}