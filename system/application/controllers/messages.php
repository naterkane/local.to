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
			if ($this->Message->add($this->postData['message'], $this->userData['username'])) 
			{
            	$this->User->sendToFollowers($this->Message->id, $this->userData['username']);
			}
            $this->redirect('/home');
        }
        else
        {
            show_404();
        }
    }

	/**
	 * Show a single status
	 *
	 * @access public
	 * @param string $username
	 * @param int $timestamp	
	 * @return 
	 */
	function view($username, $time)
	{
		$message = $this->Message->getOne($username, $time);
		if ($message) {
			$this->load->view('messages/view', array('message'=>$message));
		} else {
			show_404();
		}
	}

}

?>