<?php
/**
 * Message Class
 */
class Message extends App_Model
{

	protected $idGenerator = 'messageId';
	private $parent;

	/**
	 * Return response
	 * Used to return a response from adding regular and dm messages
	 *
	 * @access private
	 * @param array $data
	 * @return array|boolean
	 */
	private function returnResponse($data)
	{
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
	 * Set up a message
	 * Set up data for regular messages and dms. Also sets up validation values
	 *
	 * @access private
	 * @param array $data
	 * @param array $message
	 * @param array $user
	 * @param boolean $direct_message [optional] Defaults to false			
	 * @param array $to [optional] Recipient of dm	
	 * @return array Message data
	 */
	private function setUpMessage($data, $message, $user, $direct_message = false, $to = array())
	{
		if ($direct_message) 
		{
			$this->mode = 'dm';
			$data['reply_to'] = null;
			$data['reply_to_username'] = null;
			if (isset($to['id'])) 
			{
				$data['to'] = $to['id'];
			}
			else 
			{
				$data['to'] = null;				
			}
		}
		else 
		{
			$data['to'] = null;
			if ((empty($message['reply_to'])) && (empty($message['reply_username']))) 
			{
				$this->mode = 'post';
				$data['reply_to'] = null;
				$data['reply_to_username'] = null;		
			}
			else 
			{
				$this->mode = 'reply';
				$this->parent = $this->getOne($message['reply_to']);
				if (!empty($this->parent['reply_to']))
				{
					$this->parent = $this->getOne($this->parent['reply_to']);
					$data['reply_to'] = $this->parent['reply_to'];
				}
				else
				{
					$data['reply_to'] = $message['reply_to'];

				}
				$data['reply_to_username'] = $message['reply_to_username'];		
			}
			
		}
		$data['id'] = $this->makeId($this->idGenerator);	
		$data['time'] = time();
		$data['user_id'] = $user['id'];
		$data['replies'] = array();	
		$data['message'] = str_replace("\n", " ", $message['message']);	
		$data['message_html'] = preg_replace(MESSAGE_MATCH, "'\\1@<a href=\"/\\2\">\\2</a>'", $message['message']);
		$data['message_html'] = preg_replace(GROUP_MATCH, "'\\1!<a href=\"/group/\\2\">\\2</a>'", $data['message_html']);
		return $data;
	}

    /**
     * Add a new message
     *
     * @param array $message
     * @param array $user
	 * @return boolean|data
     */
    public function add($message = array(), $user = array())
    {
		if ((empty($message)) || (empty($user)))
		{
			return false;
		}
		$data = array();	
		$data = $this->setUpMessage($data, $message, $user);
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
			array_push($this->parent['replies'],$data['id']);
			$this->save($this->prefixMessage($this->parent['id']), $this->parent);
		}
		if (!$user['locked']) 
		{
			$this->addToPublicTimeline($data);
		}
		return $this->returnResponse($data);
    }

	/**
     * Add a new direct message
     *
     * @param array $message
     * @param array $user
	 * @return boolean|data
     */
    public function addDm($message = array(), $user = array())
    {
		if ((empty($message)) || (empty($user)) || (!isset($message['to'])))
		{
			return false;
		}
		$data = array();	
		$to = $this->User->getByUsername($message['to']);
		$data = $this->setUpMessage($data, $message, $user, true, $to);
		$this->startTransaction();
		if ($this->save($this->prefixMessage($data['id']), $data)) 
		{
			$this->mode = null;
			array_unshift($user['sent'], $data['id']);
			$this->User->save($this->prefixUser($user['id']), $user);
			array_unshift($to['inbox'], $data['id']);
			$this->User->save($this->prefixUser($to['id']), $to);
		}
		return $this->returnResponse($data);
    }

	/**
	 * Add to public timeline
	 *
	 * @access public
	 * @param int $message_id
	 * @return 
	 */
    public function addToPublicTimeline($message)
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
    public function addToReplies($reply_to, $message_id)
	{
		return $this->push($this->prefixReplies($reply_to), $message_id);
	}

    /**
     * Get more than one message
     *
	 * @access public
     * @param object $messages
     * @return array of messages
     */
    public function getMany($messages = array())
    {
        $return = array();
		$threading = false;
		if ($this->userData && $this->userData['threading'] == 1)
		{
			$threading = true;
			//echo"poop";
		}
		if (($messages) AND (is_array($messages))) {
			foreach ($messages as $message)
	        {
				if ($message) 
				{
					$return[] = $this->getOne($message);
					if ($threading && !empty($message['reply_to']))
					{
						foreach($this->getMany($this->prefixReplies($message['reply_to'])) as $replyid)
						{
							$return[]['replies'][] = $this->getOne($replyid); 
						}
						
					}
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
    public function getOne($message_id)
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
     * @access public
     * @param int $messages
     * @return array Messages
     */
    public function getReplies($message_id)
    {
        $messages = $this->find($this->prefixReplies($message_id));
        return $this->getMany($messages);
    }

    /**
     * Get Public Timeline
     *
     * @todo Clip the timeline at a limited number of messages
     * @access public
     * @return array Messages
     */
    public function getTimeline()
    {
        $messages = $this->find($this->prefixPublic());
        return $this->getMany($messages);
    }

    /**
     * Get Public Timeline Threaded
     *
     * @todo Clip the timeline at a limited number of messages
     * @access public
     * @return array Messages
     */
    public function getTimelineThreaded()
    {
        $messages = $this->find($this->prefixPublicThreaded());
        return $this->getMany($messages);
    }

    /**
     * Is a message is a real message
     *
     * @access public
     * @return boolean
     */
    public function isAMessage()
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
	 * Is a user a follower
	 *
	 * @access public
	 * @return boolean
	 */
	public function isFollower()
	{
		return true;
	}
	

    /**
     * Is a message a not a reply
     *
     * @access public
     * @return boolean
     */
    public function isNotAReply()
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
     * @access public
     * @return boolean
     */
    public function userOwnsMessage()
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
    public function validate()
	{
		$this->setAction();	
		if (($this->mode == 'post') || ($this->mode == 'reply') || ($this->mode == 'dm'))
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
		if ($this->mode == 'dm') 
		{
			$this->validates_presence_of('to', array('message'=>'A recipient is required'));			
			$this->validates_callback('isFollower', 'to', array('message'=>'This user is not following you'));
		}
	    return (count($this->validationErrors) == 0);
	}

}
?>
