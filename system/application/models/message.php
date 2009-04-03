<?php
/**
* Message Class
*/
class Message extends App_Model {
	

	/**
	 * Add a new message
	 *
	 * @todo Validation and return value, add to followers
	 * @param string $message
	 */
	function addMessage($message = null, $username) {
		$message_id = $this->redis->incr("global:nextPostId");
		$message = str_replace("\n", " ", $message);
		$message = $username . "|" . time(). "|" . $message;
		$this->redis->set("message:$message_id", $message);
		//$this->sendToFollowers($message_id, $username);
		//$followers = $r->smembers("uid:".$User['id'].":followers");
		//if ($followers === false) $followers = Array();
		//$followers[] = $User['id']; /* Add the post to our own posts too */
		//foreach($followers as $fid) {
		//   $r->push("uid:$fid:posts",$postid,false);
		//}
		# Push the post on the timeline, and trim the timeline to the
		# newest 1000 elements.
		$this->redis->push('messages:' . $username, $message_id, false);
		$this->redis->push('global:timeline', $message_id, false);
		$this->redis->ltrim('global:timeline', 0 , 1000);
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
	
	/**
	 * Get a single message
	 * 
	 * @return string Message
	 */	
	function get($id) {
	    return $id."|".$this->redis->get("message:$id");
	}
	
	function getMany($messages) {
		$return = array();		
		foreach ($messages as $id) {
			$return[] = $this->get($id);
		}
		return $return;
	}

	function getForUser($username = null) {
	    $messages =  $this->redis->lrange('messages:' . $username, 0, 0+1000);
		return $this->getMany($messages);	
	}

	function sendToFollowers($value='') {
		$this->redis->push('global:timeline', $message_id, false);		
	}

}

?>