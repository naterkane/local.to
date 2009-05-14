<?php
/**
 * Message Class
 */
class Message extends App_Model
{

	protected $idGenerator = 'messageId';
	private $parent;

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
		$data = array();		
		if ((empty($message['reply_to'])) && (empty($message['reply_username']))) 
		{
			$this->mode = 'post';
			$data['reply_to'] = null;
			$data['reply_to_username'] = null;			
		}
		else 
		{
			$this->mode = 'reply';
			$data['reply_to'] = $message['reply_to'];
			$data['reply_to_username'] = $message['reply_to_username'];	
			$this->parent = $this->getOne($message['reply_to']);
		}
		$time = time();
		$this->loadModels(array('Group'));
		$data['id'] = $this->makeId($this->idGenerator);	
		$data['time'] = $time;
		$data['user_id'] = $user['id'];
		$data['reply_count'] = 0;		
		$data['message'] = str_replace("\n", " ", $message['message']);	
		$data['message_html'] = preg_replace(MESSAGE_MATCH, "'\\1@<a href=\"/\\2\">\\2</a>'", $message['message']);
		$data['message_html'] = preg_replace(GROUP_MATCH, "'\\1!<a href=\"/group/\\2\">\\2</a>'", $data['message_html']);
		$this->startTransaction();
		$this->save($this->prefixMessage($data['id']), $data);
		$this->mode = null;
		array_unshift($user['private'], $data['id']);
		array_unshift($user['public'], $data['id']);
		$this->User->save($this->prefixUser($user['id']), $user);
		if ($data['reply_to']) 
		{
			$this->addToReplies($data['reply_to'], $data['id']);
		}
		if (($this->parent) AND (!$user['locked']))
		{
			++$this->parent['reply_count'];
			$this->save($this->prefixMessage($this->parent['id']), $this->parent);
		}
		if (!$user['locked']) 
		{
			$this->addToPublicTimeline($data);
		}
		$response = $this->endTransaction();
		if ($response) 
		{
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
	function addToPublicTimeline($message)
	{
		if (empty($message['reply_to'])) 
		{
			$this->push($this->prefixPublicThreaded(), $message['id']);
	        $this->trim($this->prefixPublicThreaded(), 0, 1000);			
		}
		$this->push($this->prefixPublic(), $message['id']);
		$this->trim($this->prefixPublic(), 0, 1000);
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
     * Get Public Timeline Threaded
     *
     * @todo figure out how the end and start values work
     * @return array Messages
     */
    function getTimelineThreaded()
    {
        $messages = $this->find($this->prefixPublicThreaded());
        return $this->getMany($messages);
    }

    /**
     * Is a message is a real message
     *
     * @return boolean
     */
    function isAMessage()
    {
		if (empty($this->parent))
		{
			return false;
		}
		else 
		{
			return true;
		}
    }

    /**
     * Is a message a not a reply
     *
     * @return boolean
     */
    function isNotAReply()
    {
		if (empty($this->parent['reply_to']))
		{
			return true;
		}
		else 
		{
			return false;
		}
    }

    /**
     * Is a message's owner correct?
     *
     * @return boolean
     */
    function userOwnsMessage()
    {
		$user = $this->User->get($this->parent['user_id']);
		if ($user['username'] == $this->modelData['reply_to_username']) 
		{
			return true;
		}
		else 
		{
			return false;
		}
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
		if (($this->mode == 'post') || ($this->mode == 'reply'))
		{
			$this->validates_length_of('message', array('min'=>1, 'max'=>140, 'message'=>'A message must be between 1 and 140 characters'));
			$this->validates_presence_of('message', array('message'=>'A message must be between 1 and 140 characters'));
		}
		if ($this->mode == 'reply') 
		{
			$this->validates_callback('isNotAReply', 'reply_to', array('message'=>'You can not reply to a reply'));
			$this->validates_callback('isAMessage', 'reply_to', array('message'=>'The message does not exist'));
			$this->validates_callback('userOwnsMessage', 'reply_to', array('message'=>'The user does not own this message'));
		}
	    return (count($this->validationErrors) == 0);
	}

}
?>
