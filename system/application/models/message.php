<?php
/**
 * Message Class
 */
class Message extends App_Model
{

	protected $idGenerator = 'messageId';
	protected $name = 'Message';	
	private $to = array();
	private $parent;
		
    /**
     * Add a new message
     *
     * @param array $message
     * @param array $user
	 * @return boolean
     */
    public function add($message, $user)
    {
		$this->startTransaction();
		$this->loadModels(array('Group'));		
		$data = $this->setUpMessage($message, $user);
		if ($data['dm']) 
		{
			if ($this->save($data))
			{
				$this->mode = null;
				if ($data['dm_group']) 
				{
					$this->Group->addToInbox($this->to, $data['id']);
					$this->User->addToSent($user, $data['id']);					
					$this->Group->dm($this->to['members'], $user, $data);
				}
				else 
				{
					$this->User->addToInbox($this->to, $data['id']);					
					$this->User->sms($this->to, $user, $data['message']);
					$this->User->addToSent($user, $data['id']);					
				}
			}
		}
		else 
		{
			if ($this->save($data))
			{
				$this->mode = null;
				$this->User->addToPublicAndPrivate($user, $data['id']);	
				if (!$user['locked']) 
				{
					$this->addToPublicTimeline($data);
				}
				$this->User->mode = null;
		    	$this->Group->sendToMembers($data, $this->userData['id']);
		    	$this->User->sendToFollowers($data['id'], $this->userData['followers']);
				if (isset($data['reply_to']))
				{
					$parentmessage = $this->getOne($data['reply_to']);
					$this->addToReplies($parentmessage, $data['id']);
				}
			}
		}
		return $this->endTransaction();	
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
		if (!empty($message['reply_to'])) 
		{
			$name = 'timeline_threaded';
		}
		else 
		{
			$name = 'timeline';
		}
		$pt = $this->find(null, array('override'=>$name));
		if (empty($pt['messages'])) 
		{
			$pt['messages'] = array();
		}
		array_unshift($pt['messages'], $message['id']);
		return $this->save($pt, array('override'=>'timeline', 'validate'=>false));
	}
	
	/**
	 * Add to replies
	 *
	 * @access public
	 * @param array $user 
	 * @param array $message_id 
	 * @return array $message
	 */
	public function addToReplies(&$message, $id)
	{
		return $this->addTo('replies', $message, $id, true);
	}

	/**
	 * Get more than one message
	 *
	 * @access public
     * @param array $messages
     * @return array of messages
     */
    public function getMany($messages = array(), $start = null, $end = null)
    {
		if (($start !== null) && ($end !== null) && (is_array($messages)))
		{
			$messages = array_slice($messages, $start, $end);	
		}
        $return = array();
		$threading = false;
		if ($this->userData && $this->userData['threading'] == 1)
		{
			$threading = true;
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
		$message = $this->find($message_id);
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
    public function getReplies($message_id,$start = null)
    {
        $messages = $this->find($message_id);
        return $this->getMany($messages);
    }

    /**
     * Get Public Timeline
     *
     * @todo Clip the timeline at a limited number of messages
     * @access public
     * @return array Messages
     */
    public function getTimeline($start = null)
    {
        return $this->find(null, array('override'=>'timeline'));
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
        return $this->find(null, array('override'=>'timeline_threaded'));
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
		return in_array($this->modelData['to'], $this->userData['followers']);
	}
	
	/**
	 * Is a user a member of group
	 * used for validating group DMs
	 *
	 * @access public
	 * @return boolean
	 */
	public function isGroupMember()
	{
		if (isset($this->to['members'])) 
		{
			return $this->Group->isMember($this->to['members'], $this->userData['id']);
		}
		return false;
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
	public function setUpMessage($message, $user)
	{
		$data = array();
		//check if the message is a dm even not sent through dm form
		$parts = split(" ", $message['message'], 3);
		$data['dm_group'] = false;	
		$data['to'] = null;
		$data['dm'] = false;			
		if ((strtolower($parts[0]) == 'd') AND isset($parts[1]) AND isset($parts[2]))
		{
			$data['dm'] = true;
			$data['to'] = $parts[1];
			$message['to'] = $parts[1];
			$message['message'] = $parts[2];
		}
		if (isset($message['to'])) //if it's a dm of some sort
		{
			if ($data['to'][0] == '!') 
			{
				$data['dm_group'] = true;
			}		
			$data['reply_to'] = null;
			$data['reply_to_username'] = null;			
			if ($data['dm_group']) 
			{
				$this->mode = 'dm_group';
				$data['dm'] = true;	//set this twice				
				$groupname = str_replace('!', '', $data['to']);
				$this->to = $this->Group->getByName($groupname);
			} 
			else 
			{
				$this->mode = 'dm';
				$data['dm'] = true;	//set this twice
				$this->to = $this->User->getByUsername($message['to']);
			}
			if (isset($this->to['id'])) 
			{
				$data['to'] = $this->to['id'];
			}
			else 
			{
				$data['to'] = null;				
			}
		}
		else //otherwise check if it is a reply
		{
			$data['to'] = null;
			$data['dm'] = false;	
			$data['group_dm'] = false;			
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
		//set up the rest of the message		
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
		if (($this->mode == 'post') || ($this->mode == 'reply') || ($this->mode == 'dm') || $this->mode == 'dm_group')
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
		if ($this->mode == 'dm_group') 
		{
			$this->validates_presence_of('to', array('message'=>'A recipient is required'));
			$this->validates_callback('isGroupMember', 'to', array('message'=>'You can not post to this group'));			
		}
	    return (count($this->validationErrors) == 0);
	}

}
?>