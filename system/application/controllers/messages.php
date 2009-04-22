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
     * @return
     */
    function add()
    {
        $this->mustBeSignedIn();
        if ($this->postData)
        {
			$message_id = $this->Message->add($this->postData['message'], $this->userData['id']);
			if ($message_id) 
			{
				$this->User->mode = null;
            	$this->User->sendToFollowers($message_id, $this->userData['id']);
			}
            $this->redirect('/home');
        }
        else
        {
            show_404();
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
	function view($username, $message_id)
	{
		$message = $this->Message->getOne($message_id);
		if (($message) AND ($message['username'] == $username)) {
			$this->load->view('messages/view', array('message'=>$message));
		} else {
			show_404();
		}
	}

}

?>