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
 * Messages
 * 
 * @package 	Nomcat
 * @subpackage	nomcat-controllers
 * @category	controller
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Messages extends App_Controller
{
   
    /**
     * Add a message
     * Used for all messages in the db, regardless of type. Model will handle that.
	   * @access public
     */
    public function add()
    {
      $this->mustBeSignedIn();
      $redirect = $this->getRedirect();
      if ($this->postData)
       {	
        if ($this->Message->add($this->postData, $this->userData)) 
        {
          $this->redirect($redirect, 'Your message has been sent.');				
			  }
			  else 
			  {
				  if (strlen($this->postData['message']) > 1000) 
				  {
					 $this->postData['message'] = substr_replace($this->postData['message'], '', 1000, strlen($this->postData['message']));
				  }
				  $this->cookie->set('message', $this->postData['message']);
          $this->redirect($redirect, 'There was an error adding your message.', 'error');
        }
       }
       else
       {
        $this->redirect('/home');
       }
    }

	/**
	 * Favorite a message
	 *
	 * @access public
	 * @param int $message_id
	 */
	public function favorite($message_id = null)
	{
		$this->mustBeSignedIn();
		$this->checkId($message_id);
		$redirect = $this->getRedirect();
		if ($this->Message->favorite( (integer) $message_id, $this->userData)) 
		{
			$message = 'The message added to your favorites';
		} 
		else 
		{
			$message = 'There was an error adding the message to your favorites';
		}
		$this->redirect($redirect, $message);		
	}

	/**
	 * Delete a message
	 *
	 * @access public
	 * @param int $id Message id	
	 */
    public function delete($id = null)
	{
		$this->mustBeSignedIn();
		$this->checkId($id);
		$redirect = $this->getRedirect();
		if ($this->Message->delete($id)) 
		{
			$message = 'The message was deleted';
			$type = 'success';
		} 
		else 
		{
			$message = 'There was an error deleting the message';
			$type = 'error';
		}
		$this->redirect($redirect, $message, $type);
	}
	
	/**
	 * View DM inbox
	 *
	 * @access public
	 */
	public function inbox()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = 'Inbox';
		$this->data['message'] = null;
		$this->data['dm'] = true;
		$this->Message->threaded = false;
        $this->data['messages'] = Page::make('Message', $this->userData['inbox']);
		$this->data['friend_select'] = $this->User->friendSelect($this->userData, $this->Group);
		$this->data['profile'] = $this->userData;
		$this->data['homeMenu'] = true;		
		$this->User->updateRead('inbox', $this->data['profile']);
		$this->load->view('messages/inbox', $this->data);
	}

	/**
	 * View sent DMs
	 *
	 * @access public
	 */
	public function sent()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = 'Sent';		
		$this->data['dm'] = true;	
		$this->data['sent'] = true;			
		$this->data['message'] = null;
		$this->Message->threaded = false;		
        $this->data['messages'] = Page::make('Message', $this->userData['sent']);
		$this->data['friend_select'] = $this->User->friendSelect($this->userData, $this->Group);	
		$this->data['profile'] = $this->userData;
		$this->data['homeMenu'] = true;				
		$this->load->view('messages/sent', $this->data);		
	}

	/**
	 * Public timeline
	 *
	 * @access public
	 */
    public function public_timeline()
	{
        $this->data['page_title'] = ucwords($this->config->item('public_timeline'));
		$pt = $this->Message->getPublicTimeline();
        $this->data['messages'] = Page::make('Message', $pt);
		$this->data['profile'] = $this->userData;
		$this->data['homeMenu'] = true;
		$this->load->view('messages/public_timeline', $this->data);
	}	

	/**
	 * Favorite a message
	 *
	 * @access public
	 * @param int $message_id
	 */
	public function unfavorite($message_id = null)
	{
		$this->mustBeSignedIn();
		$this->checkId($message_id);
		$redirect = $this->getRedirect();
		if ($this->Message->unfavorite($message_id, $this->userData)) 
		{
			$message = 'The message removed from your favorites';
			$type = 'success';
		} 
		else 
		{
			$message = 'There was an error removing the message from your favorites';
			$type = 'error';
		}
		$this->redirect($redirect, $message, $type);		
	}
	
	/**
	 * Show a single status
	 *
	 * @access public
	 * @param string $username
	 * @param int $message_id	
	 */
    public function view($username = null, $message_id = null)
	{
		
		$this->data['message'] = $this->Message->getOne($message_id);
		$user = $this->User->getByUsername($username);
		if (!$user){
			$this->redirect('/');
		}
		$this->data['page_title'] = ucwords($user['realname']);
		if (!$this->data['message']) 
		{
			$this->redirect('/'.$user['username']);
		}
		if ($this->data['message']['group_name']) 
		{
			$this->mustBeSignedIn();
			$group = $this->Group->getByName($this->data['message']['group_name']);
			if (!$this->Group->isMember($group['members'], $this->userData['id'])) 
			{
				$this->redirect('/groups/'.$this->data['message']['group_name']);				
			}
		}
		$this->data['messages'] = $this->Message->getMany($this->data['message']['replies']);
		if (($this->data['message']) AND ($user['username'] == $username)) {
			$this->data['remove_reply_context'] = true;
			$this->load->view('messages/view', $this->data);
		} else{
			$this->redirect('/'.$user['username']);
		}
	}

}