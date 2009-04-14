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
		$this->loadModels(array('Group'));
		$time = time();
		$message_id = $this->prefixMessage($username, $time);
        $message = str_replace("\n", " ", $message);
        $message = $username . "|" . $time . "|" . $message;
        $this->save($message_id, $message);
        $this->addToUser($username, $message_id);
		$groups = $this->Group->getGroups($message);
		if (!empty($groups)) 
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
		else 
		{
			$this->addToPublicTimeline($message_id);
		}
        return $message_id;
    }

	/**
	 * Add to message to user
	 *
	 * @access public
	 * @param string $username
	 * @param string $message_id
	 * @return 
	 */
	function addToUser($username, $message_id)
	{
		return $this->push($this->prefixUserMessages($username), $message_id);
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
			$this->addToUser($member, $message_id);
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
     * @param string $username[optional]
     * @return array Messages
     */
    function getForUser($username = null)
    {
        $messages = $this->find($this->prefixUserMessages($username));
        return $this->getMany($messages);
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
    function getmany($messages)
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
     *
     * @return
     * @param object $username
     */
    function getPrivate($username)
    {
        $messages = $this->find($this->prefixPrivate($username));
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
 
}

?>
