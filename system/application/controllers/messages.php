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
    function add()
    {
        $this->mustBeSignedIn();
        if ($this->postData)
        {
			$message = $this->Message->add($this->postData, $this->userData);
			if ($message) 
			{
				$this->User->mode = null;
            	$this->Group->sendToMembers($message, $this->userData['id']);
            	$this->User->sendToFollowers($message['id'], $this->userData['followers']);
				if (!empty($message['reply_to']))
				{
					$parentmessage = $this->Message->getOne($message['reply_to']);
					array_push($parentmessage['replies'], $message['id']);					
					$this->Message->save($this->Message->prefixMessage($parentmessage['id']),$parentmessage,false);
					$this->Message->addToReplies($message['reply_to'],$message['id']);
				}
				$this->redirect('/home');
			}
			else 
			{
				$this->redirect('/home', 'There was an error adding your message.', 'error');
			}
        }
        else
        {
            $this->redirect('/home');
        }
    }

	function delete($id)
	{
		//remember to decrease thread count if message is reply to
	}

	public function direct()
	{
        $this->mustBeSignedIn();
        if ($this->postData)
        {
			$message = $this->Message->addDm($this->postData, $this->userData);
			if ($message) 
			{
				$this->redirect('/inbox');
			}
			else 
			{
				$this->redirect('/inbox', 'There was an error adding your message.', 'error');
			}
        }
        else
        {
            $this->redirect('/home');
        }
	}
	
	public function inbox()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = 'Inbox';
		$this->data['message'] = null;
		$this->data['dm'] = true;
		$this->data['messages'] = $this->Message->getMany($this->userData['inbox']);
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
		$this->data['messages'] = $this->Message->getMany($this->userData['sent']);		
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
		$this->getUserData();
        $this->data['page_title'] = 'Public Timeline';		
		if (!empty($User) && ($User['threaded'] == 1) )
		{
			$this->data['messages'] = $this->Message->getTimelineThreaded();
		} 
		else 
		{
			$this->data['messages'] = $this->Message->getTimeline();
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
	 * Show a single status
	 *
	 * @access public
	 * @param string $username
	 * @param int $timestamp	
	 * @return 
	 */
	function view($username = null, $message_id = null)
	{
		$this->getUserData();
		$this->data['message'] = $this->Message->getOne($message_id);
		$this->data['messages'] = $this->Message->getReplies($message_id);		
		$user = $this->User->getByUserName($username);
		if (($this->data['message']) AND ($user['username'] == $username) AND (!isset($this->data['message']['to']))) {
			$this->load->view('messages/view', $this->data);
		} else{
			show_404();
		}
	}

}

?>