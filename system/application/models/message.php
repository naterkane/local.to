<?php
/**
 * Message Class
 */
class Message extends App_Model
{
   
    /**
     * Add a new message
     *
     * @todo Validation and return value, add transactions, move isMember to Validation
     * @param string $message
	 * @return boolean|message_id
     */
    function add($message = null, $user_id)
    {
		$data = array();
		$time = time();
		$this->mode = 'post';
		$this->loadModels(array('Group'));
		$data['id'] = $this->makeId();	
		$data['time'] = $time;
		$data['user_id'] = $user_id;
		$data['message'] = str_replace("\n", " ", $message);
		$data['message_html'] = $data['message'];
		if (!isset($data['reply_to']))
		{
			$data['reply_to'] = null;
		}
		if ($this->save($this->prefixMessage($data['id']), $data)) 
		{
			$this->mode = null;
			$groups = $this->Group->matchGroups($data['message']);
			if (!empty($groups)) 
			{
				$this->addToGroups($groups, $user_id, $data['id']);
			}
			else 
			{
				$this->addToUserPrivate($user_id, $data['id']);			
		        $this->addToUserPublic($user_id, $data['id']);
			}
			$this->addToPublicTimeline($data['id']);		
        	return $data['id'];
		} 
		else 
		{
			return false;
		}
    }	

	/**
	 * Add message to a group
	 *
	 * @access public
	 * @param string $name Name of group
	 * @param string $member Member to add
	 * @param array $group[option]
	 * @return boolean
	 */
	function addToGroup($group_id, $message_id)
	{
		$messages = $this->find($this->prefixGroupMessages($group_id));
		if (!empty($messages)) 
		{
			array_unshift($messages, $message_id);
			return $this->Group->save($this->prefixGroupMessages($group_id), $messages);
		} 
		else 
		{
			return false;
		}		
	}

	/**
	 * Add to message to many groups
	 *
	 * @access public
	 * @param array $groups
	 * @return 
	 */
	function addToGroups($groups, $user_id, $message_id)
	{
		foreach ($groups as $groupname) 
		{
			$group = $this->Group->getByName($groupname);
			if ($this->Group->isMember($group['id'], $user_id)) 
			{				
				$this->addToGroup($group['id'], $message_id);
				$members = $this->Group->getMembers($group['id']);
				foreach ($members as $member) 
				{
					if ((!empty($member['id'])) && ($user_id != $member['id'])) 
					{
						$this->addToUserPublic($member['id'], $message_id);
						$this->addToUserPrivate($member['id'], $message_id);
					}
				}
			}
		}
	}

	/**
	 * Add to public timeline
	 *
	 * @access public
	 * @param string $message_id
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
	 * @param string $username
	 * @param string $message_id
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
	 * @param string $username
	 * @param string $message_id
	 * @return 
	 */
	function addToUserPublic($user_id, $message_id)
	{
		return $this->push($this->prefixUserPublic($user_id), $message_id);
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
	 * @param string $username
	 * @param string $time	
	 * @return string $message
	 */
	function getOne($message_id)
	{
		$message = $this->find($this->prefixMessage($message_id));
		$user = $this->User->get($message['user_id']);
		$message['username'] = $user['username'];
		return $message;
	}

    /**
     * Get Messages for user
     *
     * @param string $username
     * @return array Messages
     */
    function getPublic($user_id)
    {
        $messages = $this->find($this->prefixUserPublic($user_id));
        return $this->getMany($messages);
    }

    /**
     *
     * @return
     * @param object $username
     */
    function getPrivate($user_id)
    {
        $messages = $this->find($this->prefixUserPrivate($user_id));
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
