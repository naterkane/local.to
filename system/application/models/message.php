<?php
/**
 * Message Class
 */
class Message extends App_Model
{
   
    /**
     * Add a new message
     *
     * @todo Validation and return value, add transactions, move isAMember to Validation
     * @param string $message
     */
    function add($message = null, $username)
    {
		$data = array();
		$time = time();
		$this->mode = 'post';
		$this->loadModels(array('Group'));
		$this->id = $this->prefixMessage($username, $time);	
		$data['time'] = $time;
		$data['username'] = $username;
		$data['message'] = str_replace("\n", " ", $message);
		if ($this->save($this->id, $data)) 
		{
			$this->addToUserPrivate($username, $this->id);
			$groups = $this->Group->getGroups($data['message']);
			if (!empty($groups)) 
			{
				$this->addToGroups($groups, $username, $this->id);
			}
	        $this->addToUserPublic($username, $this->id);	
			$this->addToPublicTimeline($this->id);			
        	return true;			
		} 
		else 
		{
			return false;
		}
    }

	/**
	 * Add to message to user's private facing list
	 *
	 * @access public
	 * @param string $username
	 * @param string $message_id
	 * @return 
	 */
	function addToUserPrivate($username, $message_id)
	{
		return $this->push($this->prefixUserPrivate($username), $message_id);
	}

	/**
	 * Add to message to user's public facing list
	 *
	 * @access public
	 * @param string $username
	 * @param string $message_id
	 * @return 
	 */
	function addToUserPublic($username, $message_id)
	{
		return $this->push($this->prefixUserPublic($username), $message_id);
	}	
  
	/**
	 * Add to message to group
	 *
	 * @access public
	 * @param string $groupname
	 * @param string $message_id
	 * @return 
	 */
	function addToGroup($groupname, $message_id)
	{
		$this->push($this->prefixGroupMessages($groupname), $message_id);
		$members = $this->Group->getMembers($groupname);
		foreach ($members as $member) 
		{
			$this->addToUserPrivate($member, $message_id);
			$this->addToUserPublic($member, $message_id);			
		}
	}

	/**
	 * Add to message to many groups
	 *
	 * @access public
	 * @param array $groups
	 * @return 
	 */
	function addToGroups($groups, $username, $message_id)
	{
		foreach ($groups as $group) 
		{
			if ($this->Group->isAMember($group, $username)) 
			{
				$this->addToGroup($group, $message_id);
			} 
			else 
			{
				$this->addToPublicTimeline($message_id);
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
     * Get the messages of the people a user is following
     *
     * @return
     * @param string $username
     * @todo 
     */
    function getFollowed($username)
    {		
        return null;
    }
  
    /**
     * Get Messages for user
     *
     * @param string $groupname[optional]
     * @return array Messages
     */
    function getForGroup($groupname = null)
    {
        $messages = $this->find($this->prefixGroupMessages($groupname));
        return $this->getMany($messages);
    }

    /**
     * Get more than one message
     *
     * @return
     * @param object $messages
     */
    function getMany($messages)
    {
        $return = array();
		if ($messages) {
			foreach ($messages as $id)
	        {
				if ($id) 
				{
					$return[] = $this->find($id);
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
	function getOne($username, $time)
	{
		return $this->find($this->prefixMessage($username, $time));
	}

    /**
     * Get Messages for user
     *
     * @param string $username[optional]
     * @return array Messages
     */
    function getPublic($username = null)
    {
        $messages = $this->find($this->prefixUserPublic($username));
        return $this->getMany($messages);
    }

    /**
     *
     * @return
     * @param object $username
     */
    function getPrivate($username)
    {
        $messages = $this->find($this->prefixUserPrivate($username));
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
	 * Validates a user
	 *
	 * @access public
	 * @return 
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
