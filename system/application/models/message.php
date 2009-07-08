<?php
 /**
 * Nomcat
 *
 * An open source microsharing platform built on CodeIgniter
 *
 * @package		Nomcat
 * @author		NOM
 * @copyright	Copyright (c) 2009, NOM llc.
 * @license		http://creativecommons.org/licenses/by-sa/3.0/
 * @link		http://getnomcat.com
 * @version		$Id$
 * @filesource
 */
/**
 * Message Model
 * 
 * @package 	Nomcat
 * @subpackage	Models
 * @category	Model
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Message extends App_Model
{

	/**
	 * All fields for model, any other data sent to model is not saved, alphabetized
	 * @access protected
	 * @var array
	 */
	protected $fields = array(
			/**
			 * id of message
			 * @var integer
			 */
			'id' => null,
			/**
			 * Timestamp when the record was created
			 * @var integer
			 */
			'created' => null,
			/**
			 * Is this a direct message?
			 * @var boolean
			 */
			'dm' => false,
			/**
			 * Is this a direct message to a group?
			 * @var boolean
			 */
			'dm_group' => false,
			/**
			 * Plain text of message
			 * @var string
			 */
			'message' => null,
			/**
			 * HTML version of message
			 * @var string
			 */
			'message_html' => null,
			/**
			 * Timestamp when the record was last modified
			 * @var integer
			 */
			'modified' => null,
			/**
			 * Array of message ids replying to this message
			 * @var array
			 */
			'replies' => array(),
			/**
			 * Id of message this is in reply to
			 * @var integer
			 */
			'reply_to' => null,
			/**
			 * Username of user this message is in reply to
			 * @var string
			 */
			'reply_to_username' => null,
			/**
			 * Datetime message was created
			 * @var integer
			 */
			'time' => null,
			/**
			 * Id of user direct message is sent to
			 * @var integer
			 */
			'to' => null,
			/**
			 * Id of message's owner/author
			 * @var integer
			 */
			'user_id' => null,
			/**
			 * username of message's owner/author
			 * @var string
			 */
			'username' => null
			);
	/**
	 * @access protected
	 * @var string
	 */
	protected $idGenerator = 'messageId';
	/**
	 * @access protected
	 * @var string
	 */
	protected $name = 'Message';
	/**
	 * @access private
	 * @var array
	 */
	private $groupMentions = array();	
	/**
	 * @access private
	 * @var array
	 */	
	private $to = array();
	/**
	 * @access private
	 * @var array
	 */
	private $userMentions = array();
	/**
	 * @access private
	 * @var mixed
	 */
	private $parent;

    /**
     * Add a new message
     *
     * @param string $message
     * @param array $user
	 * @return boolean
     */
    public function add($message, $user)
    {
		$this->startTransaction();
		$this->loadModels(array('Group'));		
		$data = $this->parse($message, $user);
		
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
				foreach ($this->userMentions as $mention_username => $user_mention) {
					// query here just in case the user is mentioning herself, which would reset the data
					$mention = $this->User->getByUsername($mention_username);
					if ($mention) 
					{
						$this->User->addToMentions($mention, $data['id']);
					}					
				}				
				foreach ($this->groupMentions as $mention_groupname => $group_mention) {
					// query here just in case the user is mentioning herself, which would reset the data
					$group_mention = $this->Group->getByName($mention_groupname);
					if ($group_mention) 
					{
						$this->Group->addToMentions($group_mention, $data['id']);
					}					
				}								
				if ($data['reply_to'] != null)
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
	 * @param integer $message_id
	 * @return 
	 */
    public function addToPublicTimeline($message)
	{
		$pt = $this->find(null, array('override'=>'timeline'));
		if (!isset($pt['threaded'])) 
		{
			$pt['threaded'] = array();
		}
		if (!isset($pt['unthreaded'])) 
		{
			$pt['unthreaded'] = array();
		}		
		if (!empty($message['reply_to'])) 
		{
			array_unshift($pt['threaded'], $message['id']);
		}
		else 
		{
			array_unshift($pt['unthreaded'], $message['id']);
		}
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
	 * Favorite a message
	 *
	 * @access public
	 * @param integer $message_id
	 * @param array $user	
	 * @return boolean
	 */
	public function favorite($message_id = null, $user = array())
	{
		$message = $this->find($message_id);
		if (empty($message) || !isset($user['favorites'])) 
		{
			return false;
		}
		if (in_array($message_id, $user['favorites'])) 
		{
			return false;
		}
		return $this->User->addTo('favorites', $user, $message_id);
	}
	

	/**
	 * Get more than one message
	 *
	 * @access public
     * @param array $messages
     * @return array of messages
     */
    public function getMany($messages = array(), $start = null, $end = null,$options = array())
    {
		$return = array();
		//var_dump($options);
		$isreplies = (isset($options['isreplies']))?$options['isreplies']:false;
		$threading = (isset($options['threading']))?$options['threading']:($this->userData && ($this->userData['threading'] == 0))?false:true;
		//$isreplies = (isset($options['isreplies']))?$options['isreplies']:false;
		$end = ($end != null)? $end : count($messages);
		//$origmessages = $messages;
		//if ($isreplies == null) $this->firephp->log(count($messages),"total messages");
		if ($isreplies == false && ($start !== null) && ($end !== null) && (is_array($messages)))
		{
			
			//$messages = array_slice($messages, $start, $end,true);	
		} 
        
		$this->firephp->log($threading,"threading");
		if (($messages) AND (is_array($messages))) {
			
			foreach ($messages as $message)
	        {
				if ($message && count($return)<$end) 
				{
					$messageid = $message;
					$message = $this->getOne($message);
					$message['id'] = $messageid;
					if ($isreplies != true && $threading == true && empty($message['reply_to']))
					{
						//echo "poop";
						//$this->firephp->trace("showing threading");
						//if (is_int($message['replies'][0])):
							$this->firephp->log($message['replies']);
							
							//$replies = $this->getMany($message['replies'], null, null,"replies");
							//$this->firephp->log($replies);
							$replies = $message['replies'];
							//$message['replies'] = array();
							foreach($replies as $key => $reply)
							{
								//echo $key;
								$message['replies'][$key] = $this->getOne($reply);
							}		
						//endif;
						$return[] = $message;
					//} elseif (!$replies && !empty($message['reply_to'])) {
					//	$this->firephp->trace("elseif ".!$replies && !empty($message['reply_to']));
					} elseif ($isreplies != true && $threading==true && !empty($message['reply_to'])) {
						//echo "double poop";
					}else{
						//$this->firephp->trace("else");
						//echo "yay";
						$return[] = $message;
					}
					//$this->firephp->log($message['id']);
				}
	        }
			//$this->firephp->log(count($return),"final return count");
			/*if (($start !== null) && ($end !== null) && count($return)<$end){
				$this->firephp->log($start,"start");
				$this->firephp->log($end,"end");
				$this->firephp->log(count($return),"return count");
				$diff = $end - count($return);
				$this->firephp->log($diff,"diff");
				if ($diff > 0 && count(array_slice($origmessages,$start+$end,$diff)) > 0)
				{
					$this->firephp->trace("moremessages start");
					$moremessages = self::getMany($origmessages,$start+$end,$diff);
					$this->firephp->trace("moremessages stop");
					foreach($moremessages as $moremessage)
					{
						if (count($return<20))
						{
							$return[] = $moremessage;
						}
					}
				}
			}*/
			
		}
		$return['threading'] = $threading;
		$return['isreplies'] = $isreplies;
		//echo "is replies "; var_dump($isreplies);
        return $return;
    }

	/**
	 * Get one message
	 *
	 * @access public
     * @param integer $message_id
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
     * @param integer $messages
     * @return array Messages
     */
    public function getReplies($message_ids, $start = null)
    {
        return $this->getMany($message_ids,$start,null,array("isreplies"=>true));
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
        $pt = $this->find(null, array('override'=>'timeline'));
		$return = (!empty($pt['unthreaded']))?$pt['unthreaded']:null;
		return $return;
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
        $pt = $this->find(null, array('override'=>'timeline'));
		$return = (!empty($pt['threaded']))?$rt['threaded']:null;
		return $return;
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
		return $this->Group->isMember($this->to['members'], $this->userData['id']);
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
	 * Parse up a message
	 * Set up data for regular messages and dms. Also sets up validation values
	 *
	 * @access public
	 * @param array $message
	 * @param array $user
	 * @return array Message data
	 */
	public function parse($message, $user)
	{
		$data = $this->create($message);
		
		//check if the message is a dm even not sent through dm form
		$parts = split(" ", $data['message'], 3);
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
			if ((empty($message['reply_to'])) && (empty($message['reply_username']))) 
			{
				$this->mode = 'post';
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
		$data['message'] = str_replace("\n", " ", $data['message']);	
		$data['message_html'] = preg_replace(MESSAGE_MATCH, "'\\1@<a href=\"/\\2\">\\2</a>'", $message['message']);
		if ($data['message'] != $data['message_html']) 
		{
			$this->parseMentions($data, '@', 'userMentions');
		}
		$temp = $data['message_html'];
		$data['message_html'] = preg_replace(GROUP_MATCH, "'\\1!<a href=\"/group/\\2\">\\2</a>'", $data['message_html']);
		if ($temp != $data['message_html']) 
		{
			$this->parseMentions($data, '!', 'groupMentions');
		}
		$data['message_html'] = preg_replace(URL_MATCH, '<a href="$1">$1</a>', $data['message_html']);		
		return $data;
	}
	
	/**
	 * Parse a message to look for mentions
	 *
	 * @access private
	 * @param array $data
	 * @param string $separator @ or !	
	 * @param string $property userMention, groupMention, etc.
	 * @return 
	 */
	private function parseMentions($data, $separator, $property)
	{
		$parts = explode(' ', $data['message']);
		foreach ($parts as $part) {
			if ($part[0] == $separator) 
			{
				$username = str_replace($separator, '', $part);
				$this->{$property}[$username] = array(); //set to id so that each user only gets one mention
			}
		}
	}

	/**
	 * Unfavorite a message
	 *
	 * @access public
	 * @param integer $message_id
	 * @param array $user	
	 * @return boolean
	 */
	public function unfavorite($message_id = null, $user = array())
	{
		$message = $this->find($message_id);
		if (empty($message) || !isset($user['favorites'])) 
		{
			return false;
		}
		if (!in_array($message_id, $user['favorites'])) 
		{
			return false;
		}
		$user['favorites'] = $this->User->removeFromArray($user['favorites'], $message_id);
		return $this->User->save($user);
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