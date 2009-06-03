<?php
/**
 * Class for users controller
 */
class Messages extends App_Controller
{
   
    /**
     * Add a message
     *
     * @todo Make ajax
     * @todo Find out where `<script>` tags are being set to `[removed]` and make sure they're just escaped like other tags.
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
	 * @param id $message_id
	 * @return 
	 */
	public function favorite($message_id = null)
	{
		$this->mustBeSignedIn();
		$this->checkId($message_id);
		$redirect = $this->getRedirect();
		if ($this->Message->favorite($message_id, $this->userData)) 
		{
			$message = 'The message added to your favorites';
		} 
		else 
		{
			$message = 'There was an error adding the message to your favorites';
		}
		$this->redirect($redirect, $message);		
	}

	function delete($id)
	{
		//remember to decrease thread count if message is reply to
	}
	
	public function inbox()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = 'Inbox';
		$this->data['message'] = null;
		$this->data['dm'] = true;
        $this->data['messages'] = Page::make('Message', $this->userData['inbox']);
		$this->data['friend_select'] = $this->User->friendSelect($this->userData['followers']);	
		$this->load->view('messages/inbox', $this->data);
	}
	
	public function sent()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = 'Sent';		
		$this->data['dm'] = true;	
		$this->data['sent'] = true;			
		$this->data['message'] = null;
        $this->data['messages'] = Page::make('Message', $this->userData['sent']);
		$this->data['friend_select'] = $this->User->friendSelect($this->userData['followers']);			
		$this->load->view('messages/sent', $this->data);		
	}

	/**
	 * Public timeline
	 *
	 * @access public
	 */
	function public_timeline()
	{
        $this->data['page_title'] = 'Public Timeline';		
		if (!empty($User) && ($User['threaded'] == 1) )
		{
        	$pt = $this->Message->getTimelineThreaded();
        	$this->data['messages'] = Page::make('Message', $pt);
		} 
		else 
		{
        	$pt = $this->Message->getTimeline();
        	$this->data['messages'] = Page::make('Message', $pt);
		}
		$this->load->view('messages/public_timeline', $this->data);
	}
	
	/**
	 * Threaded public timeline
	 *
	 * @access public
	 */
	function public_timeline_threaded()
	{
		$this->public_timeline(true);
	}	

	/**
	 * Favorite a message
	 *
	 * @access public
	 * @param id $message_id
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
	 * @param int $timestamp	
	 * @return 
	 */
	function view($username = null, $message_id = null)
	{
		$this->data['message'] = $this->Message->getOne($message_id);
		$this->data['messages'] = $this->Message->getReplies($this->data['message']['replies']);		
		$user = $this->User->getByUserName($username);
		if (($this->data['message']) AND ($user['username'] == $username) AND (!isset($this->data['message']['to']))) {
			$this->load->view('messages/view', $this->data);
		} else{
			$this->show404();
		}
	}

}

?>