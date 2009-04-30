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
            	$this->User->sendToFollowers($message['id'], $this->userData['id']);
			}
            $this->redirect('/home');
        }
        else
        {
            $this->redirect('/home');
        }
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
		$this->data['messages'] = $this->Message->getTimeline();
		$this->load->view('messages/public_timeline', $this->data);
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
		if (($this->data['message']) AND ($user['username'] == $username)) {
			$this->load->view('messages/view', $this->data);
		} else{
			show_404();
		}
	}

}

?>