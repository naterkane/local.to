<?php
/**
* Message Class
*/
class Message extends App_Model {
	

	/**
	 * Add a new message
	 *
	 * @todo Validation and return value, add transactions
	 * @param string $message
	 */
	function addMessage($message = null, $username) {
		$message_id = $this->redis->incr("global:nextPostId");
		$message = str_replace("\n", " ", $message);
		$message = $username . "|" . time(). "|" . $message;
		$this->redis->set("message:$message_id", $message);
		$this->redis->push('messages:' . $username, $message_id, false);
		$this->redis->push('global:timeline', $message_id, false);
		$this->redis->ltrim('global:timeline', 0 , 1000);
		return $message_id;
	}
	
	/**
	 * Get a single message
	 * 
	 * @return string Message
	 */	
	function get($id) {
	    return $id."|".$this->redis->get("message:$id");
	}
	
	/**
	 * Get people user is following
	 */
	function getFollowed($username) {
		//$users = $this->redis->lrange('following:' . $username, 0, 0+1000);
		//foreach ($users as $username) {
		//	$user = $this->getForUser($username);
		//}
		//return $this->getMany($messages);
	}

	function getForUser($username = null) {
	    $messages =  $this->redis->lrange('messages:' . $username, 0, 0+1000);
		return $this->getMany($messages);	
	}
	
	function getMany($messages) {
		$return = array();		
		foreach ($messages as $id) {
			$return[] = $this->get($id);
		}
		return $return;
	}

	function getPrivate($username) {		
	    $messages =  $this->redis->lrange('private:' . $username, 0, 0+1000);
		return $this->getMany($messages);
	}

	/**
	 * Get Public Timeline
	 * 
	 * @todo figure out how the end and start values work
	 * @return array Messages
	 */
	function getTimeline() {
		$messages = $this->redis->lrange('global:timeline', 0, 0+1000);
		return $this->getMany($messages);
	}

}

?>