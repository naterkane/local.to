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
     * @return
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
	 * @return 
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
	 * @return
	 */
    public function delete($id = null)
	{
		$this->mustBeSignedIn();
		$this->checkId($id);
		$redirect = $this->getRedirect();
		if ($this->Message->delete($id)) 
		{
			$message = 'The message was deleted';
		} 
		else 
		{
			$message = 'There was an error deleting the message';
		}
		$this->redirect($redirect, $message);
	}
	
	/**
	 * View DM inbox
	 *
	 * @access public
	 * @return
	 */
	public function inbox()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = 'Inbox';
		$this->data['message'] = null;
		$this->data['dm'] = true;
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
	 * @return
	 */
	public function sent()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = 'Sent';		
		$this->data['dm'] = true;	
		$this->data['sent'] = true;			
		$this->data['message'] = null;
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
	 * @return
	 */
    public function public_timeline()
	{
        $this->data['page_title'] = 'Public Timeline';
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
	 * @return 
	 */
	public function unfavorite($message_id = null)
	{
		$this->mustBeSignedIn();
		$this->checkId($message_id);
		$redirect = $this->getRedirect();
		if ($this->Message->unfavorite($message_id, $this->userData)) 
		{
			$message = 'The message removed from your favorites';
		} 
		else 
		{
			$message = 'There was an error removing the message from your favorites';
		}
		$this->redirect($redirect, $message);		
	}
	
	/**
	 * Show a single status
	 *
	 * @access public
	 * @param string $username
	 * @param int $message_id	
	 * @return 
	 */
    public function view($username = null, $message_id = null)
	{
		$this->data['message'] = $this->Message->getOne($message_id);
		$this->data['messages'] = $this->Message->getMany($this->data['message']['replies']);
		$user = $this->User->getByUserName($username);
		if (($this->data['message']) AND ($user['username'] == $username) AND (!isset($this->data['message']['to']))) {
			$this->data['remove_reply_context'] = true;
			$this->load->view('messages/view', $this->data);
		} else{
			$this->show404();
		}
	}

}

?>