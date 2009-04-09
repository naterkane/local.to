<?php
/**
 * Message Class
 */
class Message extends App_Model
{
   
    
    /**
     * Add a new message
     *
     * @todo Validation and return value, add transactions
     * @param string $message
     */
    function addMessage($message = null, $username)
    {
		$time = time();
		$message_id = 'message:' . $username . ':' . $time;
        $message = str_replace("\n", " ", $message);
        $message = $username . "|" . $time . "|" . $message;
        $this->save($message_id, $message);
        $this->push('messages:' . $username, $message_id);
        $this->push('global:timeline', $message_id);
        $this->trim('global:timeline', 0, 1000);
        return $message_id;
    }
   
    /**
     * Get the messages of the people a user is following
     *
     * @return
     * @param string $username
     * @todo need to clean this up... there shouldn't be a need to manually aggregate the messages of who a person is following once we push each posted message to the List of each follower.
     */
    function getFollowed($username)
    {
		/*
        $users = $this->redis->lrange('following:'.$username, 0, 0+1000);
		$messages = array ();
		$result = array();
        foreach ($users as $user)
        {
			$messages[] = $this->redis->lrange('messages:'.$user, 0, 0+1000);
			$c = count($messages);
			for ($i=0;$i<count($messages[$c-1]);$i++){
				array_push($result,$messages[$c-1][$i]);
			}
        }
		sort($result,SORT_NUMERIC); 
		$return = array_reverse(array_unique($result));
		*/		
        return null;
    }
   
    /**
     *
     *
     * @return
     * @param string $username[optional]
     */
    function getForUser($username = null)
    {
        $messages = $this->find('messages:' . $username);
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
	            $return[] = $this->find($id);
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
		return $this->find('message:' . $username . ':' . $time);
	}

    /**
     *
     * @return
     * @param object $username
     */
    function getPrivate($username)
    {
        $messages = $this->find('private:' . $username);
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
        $messages = $this->find('global:timeline');
        return $this->getMany($messages);
    }
   
}

?>
