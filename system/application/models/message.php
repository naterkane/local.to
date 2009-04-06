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
        $message_id = $this->redis->incr("global:nextPostId");
        $message = str_replace("\n", " ", $message);
        $message = $username."|".time()."|".$message;
        $this->redis->set("message:$message_id", $message);
        $this->redis->push('messages:'.$username, $message_id, false);
        $this->redis->push('global:timeline', $message_id, false);
        $this->redis->ltrim('global:timeline', 0, 1000);
        return $message_id;
    }
   
    /**
     * Get a single message
     *
     * @return
     * @param object $id
     */
    function get($id)
    {
        //if ($message = $this->redis->get("message:$id"))
		//{
			return $id."|".$this->redis->get("message:$id");
		//}
		//else 
		//{
		//	trigger_error("Sorry we don't have a message with the ID of '".var_dump($id)."'.", E_USER_ERROR);
		//}
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
        return $this->getMany($return);
    }
   
    /**
     *
     *
     * @return
     * @param string $username[optional]
     */
    function getForUser($username = null)
    {
        $messages = $this->redis->lrange('messages:'.$username, 0, 0+1000);
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
        $return = array ();
        foreach ($messages as $id)
        {
            $return[] = $this->get($id);
        }
        return $return;
    }
   
    /**
     *
     * @return
     * @param object $username
     */
    function getPrivate($username)
    {
        $messages = $this->redis->lrange('private:'.$username, 0, 0+1000);
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
        $messages = $this->redis->lrange('global:timeline', 0, 0+1000);
        return $this->getMany($messages);
    }
   
}

?>
