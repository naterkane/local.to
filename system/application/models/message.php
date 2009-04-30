<?php
/**
 * Message Class
 */
class Message extends App_Model
{

    /**
     * Add a new message
     *
     * @todo add transactions
     * @param array $message
     * @param array $user
	 * @return boolean|data
     */
    function add($message = array(), $user = array())
    {
		if ((empty($message)) || (empty($user)))
		{
			return false;
		}
		$user_id = $user['id'];
		$data = array();
		$time = time();
		$this->mode = 'post';
		$this->loadModels(array('Group'));
		$data['id'] = $this->makeId($this->messageId);	
		$data['time'] = $time;
		$data['user_id'] = $user['id'];
		$data['message'] = str_replace("\n", " ", $message['message']);	
		$data['message_html'] = preg_replace(MESSAGE_MATCH, "'\\1@<a href=\"/\\2\">\\2</a>'", $message['message']);
		$data['message_html'] = preg_replace(GROUP_MATCH, "'\\1!<a href=\"/group/\\2\">\\2</a>'", $data['message_html']);
		$data['reply_to'] = $message['reply_to'];
		$data['reply_to_username'] = $message['reply_to_username'];
		if ($this->save($this->prefixMessage($data['id']), $data)) 
		{
			$this->mode = null;
			$this->addToUserPublic($user_id, $data['id']);
			$this->addToUserPrivate($user_id, $data['id']);
			if (!$user['locked']) 
			{
				$this->addToPublicTimeline($data['id']);
			}
			if ($data['reply_to']) 
			{
				$this->addToReplies($data['reply_to'], $data['id']);
			}
        	return $data;
		} 
		else 
		{
			return false;
		}
    }

	/**
	 * Add to public timeline
	 *
	 * @access public
	 * @param int $message_id
	 * @return 
	 */
	function addToPublicTimeline($message_id)
	{
		$this->push($this->prefixPublic(), $message_id);
        $this->trim($this->prefixPublic(), 0, 1000);
	}

	/**
	 * Add to message to user's private facing list
	 *
	 * @access public
	 * @param int $user_id	
	 * @param int $message_id
	 * @return 
	 */
	function addToUserPrivate($user_id, $message_id)
	{
		return $this->push($this->prefixUserPrivate($user_id), $message_id);
	}

	/**
	 * Add to message to user's public facing list
	 *
	 * @access public
	 * @param int $user_id	
	 * @param int $message_id
	 * @return 
	 */
	function addToUserPublic($user_id, $message_id)
	{
		return $this->push($this->prefixUserPublic($user_id), $message_id);
	}
	
	/**
	 * Add to message to message's replies
	 *
	 * @access public
	 * @param int $user_id	
	 * @param int $message_id
	 * @return 
	 */
	function addToReplies($reply_to, $message_id)
	{
		return $this->push($this->prefixReplies($reply_to), $message_id);
	}

    /**
     * Get the messages of the people a user is following
     *
     * @return
     * @param string $username
     * @todo 
     */
    function getFollowed($user_id)
    {		
        return null;
    }

	function getForGroup($group_id)
	{
		$messages = $this->find($this->prefixGroupMessages($group_id));
		return $this->getMany($messages);
	}

    /**
     * Get more than one message
     *
     * @return
     * @param object $messages
     */
    function getMany($messages = array())
    {
        $return = array();
		if (($messages) AND (is_array($messages))) {
			foreach ($messages as $id)
	        {
				if ($id) 
				{
					$return[] = $this->getOne($id);
				}
	        }
		}
        return $return;
    }

	/**
	 * Get one message
	 *
	 * @access public
     * @param int $message_id
     * @return array Message
     */
	function getOne($message_id)
	{
		$message = $this->find($this->prefixMessage($message_id));
		$user = null;
		if (!empty($message)) 
		{
			$user = $this->User->get($message['user_id']);
			$message['username'] = $user['username'];			
		}
		return $message;
	}

    /**
     * Get Private messages 
	 *
     * @access public
     * @param int $user_id
     * @return array Messages
     */
    function getPrivate($user_id)
    {
        $messages = $this->find($this->prefixUserPrivate($user_id));
        return $this->getMany($messages);
    }

    /**
     * Get Messages for user
     *
     * @access public
     * @param int $user_id
     * @return array Messages
     */
    function getPublic($user_id)
    {
        $messages = $this->find($this->prefixUserPublic($user_id));
        return $this->getMany($messages);
    }
   
    /**
     * Get replies to a message
     *
     * @param int $messages
     * @return array Messages
     */
    function getReplies($message_id)
    {
        $messages = $this->find($this->prefixReplies($message_id));
        return $this->getMany($messages);
    }

    /**
     * Get Public Timeline
     *
     * @todo figure out how the end and start values work
     * @return array Messages
     */
    function getTimeline()
    {
        $messages = $this->find($this->prefixPublic());
        return $this->getMany($messages);
    }
	
	/**
	 * Validates a message
	 *
	 * @access public
	 * @return boolean
	 */	
	function validate()
	{
		$this->setAction();		
		if ($this->mode == 'post')
		{
			$this->validates_length_of('message', array('min'=>1, 'max'=>140, 'message'=>'A message must be between 1 and 140 characters'));
			$this->validates_presence_of('message', array('message'=>'A message must be between 1 and 140 characters'));
		}
	    return (count($this->validationErrors) == 0);
	}

}
?>
