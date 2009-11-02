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
 * User Model
 * 
 * @package 	Nomcat
 * @subpackage	Models
 * @category	Model
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class User extends App_Model
{
	/**
	 * @access protected
	 * @var array
	 */
	protected $fields = array();
	/**
	 * @access protected
	 * @var string
	 */
	protected $idGenerator = 'userId';
	/**
	 * @access protected
	 * @var string
	 */
	protected $name = 'User';
	/**
	 * @var string
	 */	
	public $defaultTimeZone = 'US/Eastern';	
	/**
	 * @var array
	 */
	public $timeZones = array('US/Hawaii'=>'(GMT-10:00) Hawaii','US/Alaska'=>'(GMT-09:00) Alaska','US/Pacific' => '(GMT-07:00) Pacific Time (US &amp; Canada)','US/Arizona'=>'(GMT-07:00) Arizona','US/Mountain'=>'(GMT-07:00) Mountain Time (US &amp; Canada)','US/Central'=>'(GMT-06:00) Central Time (US &amp; Canada)','US/Eastern'=>'(GMT-05:00) Eastern Time (US &amp; Canada)','US/East-Indiana' => '(GMT-05:00) Indiana (East)','America/Tijuana'=>'(GMT-08:00) Tijuana','America/Chihuahua'=>'(GMT-07:00) Chihuahua','America/Mazatlan'=>'(GMT-07:00) Mazatlan','America/Monterrey'=>'(GMT-06:00) Monterrey','America/Mexico_City'=>'(GMT-06:00) Mexico City',		'Canada/East-Saskatchewan'=>'(GMT-06:00) Saskatchewan','Canada/Saskatchewan'=>'(GMT-06:00) Saskatchewan','America/Bogota'=>'(GMT-05:00) Bogota','America/Lima'=>'(GMT-05:00) Lima','America/Caracas'=>'(GMT-04:00) Caracas','America/Santiago'=>'(GMT-04:00) Santiago','Canada/Newfoundland'=>'(GMT-03:30) Newfoundland','Atlantic/Azores'=>'(GMT-01:00) Azores','Africa/Casablanca'=>'(GMT) Casablanca','Europe/Dublin'=>'(GMT) Dublin','Europe/Lisbon'=>'(GMT) Lisbon','Europe/London'=>'(GMT) London','Africa/Monrovia'=>'(GMT) Monrovia','Europe/Amsterdam'=>'(GMT+01:00) Amsterdam','Europe/Belgrade'=>'(GMT+01:00) Belgrade','Europe/Berlin'=>'(GMT+01:00) Berlin','Europe/Bratislava'=>'(GMT+01:00) Bratislava','Europe/Brussels'=>'(GMT+01:00) Brussels','Europe/Budapest'=>'(GMT+01:00) Budapest','Europe/Copenhagen'=>'(GMT+01:00) Copenhagen','Europe/Ljubljana'=>'(GMT+01:00) Ljubljana','Europe/Madrid'=>'(GMT+01:00) Madrid','Europe/Paris'=>'(GMT+01:00) Paris','Europe/Prague'=>'(GMT+01:00) Prague','Europe/Rome'=>'(GMT+01:00) Rome','Europe/Sarajevo'=>'(GMT+01:00) Sarajevo','Europe/Skopje'=>'(GMT+01:00) Skopje','Europe/Stockholm'=>'(GMT+01:00) Stockholm','Europe/Vienna'=>'(GMT+01:00) Vienna','Europe/Warsaw'=>'(GMT+01:00) Warsaw','Europe/Zagreb'=>'(GMT+01:00) Zagreb','Europe/Athens'=>'(GMT+02:00) Athens','Europe/Bucharest'=>'(GMT+02:00) Bucharest','Africa/Cairo'=>'(GMT+02:00) Cairo','Africa/Harare'=>'(GMT+02:00) Harare','Europe/Helsinki'=>'(GMT+02:00) Helsinki','Asia/Istanbul'=>'(GMT+02:00) Istanbul','Europe/Istanbul'=>'(GMT+02:00) Istanbul','Asia/Jerusalem'=>'(GMT+02:00) Jerusalem','Europe/Minsk'=>'(GMT+02:00) Minsk','Europe/Riga'=>'(GMT+02:00) Riga','Europe/Sofia'=>'(GMT+02:00) Sofia','Europe/Tallinn'=>'(GMT+02:00) Tallinn','Europe/Vilnius'=>'(GMT+02:00) Vilnius','Asia/Baghdad'=>'(GMT+03:00) Baghdad','Asia/Kuwait'=>'(GMT+03:00) Kuwait','Europe/Moscow'=>'(GMT+03:00) Moscow','Africa/Nairobi'=>'(GMT+03:00) Nairobi','Asia/Riyadh'=>'(GMT+03:00) Riyadh','Europe/Volgograd'=>'(GMT+03:00) Volgograd','Asia/Tehran'=>'(GMT+03:30) Tehran','Asia/Baku'=>'(GMT+04:00) Baku','Asia/Muscat'=>'(GMT+04:00) Muscat','Asia/Tbilisi'=>'(GMT+04:00) Tbilisi','Asia/Yerevan'=>'(GMT+04:00) Yerevan','Asia/Kabul'=>'(GMT+04:30) Kabul','Asia/Karachi'=>'(GMT+05:00) Karachi','Asia/Tashkent'=>'(GMT+05:00) Tashkent','Asia/Almaty'=>'(GMT+06:00) Almaty','Asia/Dhaka'=>'(GMT+06:00) Dhaka','Asia/Novosibirsk'=>'(GMT+06:00) Novosibirsk','Asia/Rangoon'=>'(GMT+06:30) Rangoon','Asia/Bangkok'=>'(GMT+07:00) Bangkok','Asia/Jakarta'=>'(GMT+07:00) Jakarta','Asia/Krasnoyarsk'=>'(GMT+07:00) Krasnoyarsk','Asia/Chongqing'=>'(GMT+08:00) Chongqing','Asia/Irkutsk'=>'(GMT+08:00) Irkutsk','Australia/Perth'=>'(GMT+08:00) Perth','Asia/Singapore'=>'(GMT+08:00) Singapore','Singapore'=>'(GMT+08:00) Singapore','Asia/Taipei'=>'(GMT+08:00) Taipei','Asia/Urumqi'=>'(GMT+08:00) Urumqi','Asia/Seoul'=>'(GMT+09:00) Seoul','Asia/Tokyo'=>'(GMT+09:00) Tokyo','Asia/Yakutsk'=>'(GMT+09:00) Yakutsk','Australia/Adelaide'=>'(GMT+09:30) Adelaide','Australia/Darwin'=>'(GMT+09:30) Darwin','Australia/Brisbane'=>'(GMT+10:00) Brisbane','Australia/Canberra'=>'(GMT+10:00) Canberra','Pacific/Guam'=>'(GMT+10:00) Guam','Australia/Hobart'=>'(GMT+10:00) Hobart','Australia/Melbourne'=>'(GMT+10:00) Melbourne','Australia/Sydney'=>'(GMT+10:00) Sydney','Asia/Vladivostok'=>'(GMT+10:00) Vladivostok','Asia/Magadan'=>'(GMT+11:00) Magadan','Pacific/Auckland'=>'(GMT+12:00) Auckland','Pacific/Fiji'=>'(GMT+12:00) Fiji','Asia/Kamchatka'=>'(GMT+12:00) Kamchatka');

	/**
	 * Calls the parent constructor then loads any fields defined in the current theme's configuration into the Group::$fields array
	 */
	public function __construct()
	{
		parent::__construct();
		$this->ci = get_instance();
		if (file_exists(APPPATH . 'views/themes/' . $this->ci->config->item('theme') . '/config/user_fields.php')) 
		{
			require_once(APPPATH . 'views/themes/' . $this->ci->config->item('theme') . '/config/user_fields.php');
			$this->fields = $fields;
		}
		unset($this->ci);		
	}


	/**
	 * Private method for following
	 *
	 * @access private
	 * @param array $followed Data of user to follow
	 * @param array $following Data of user following
	 * @return 
	 */
	private function _follow(&$followed, &$following)
	{
		array_unshift($followed['followers'], $following['id']);
		$this->save($followed);
		array_unshift($following['following'], $followed['id']);
		$this->save($following);	
		return true;
	}

	/**
	 * Add a followed messages to inbox
	 *
	 * @access public
	 * @param array $followed user data
	 * @param array $following user data	
	 * @return boolean
	 */
	public function addFollowedMessages($followed, $following)
	{
		$public = array_slice($followed['public'], 0, 20);
		$this->loadModels(array('Message'));
		foreach ($public as $id) {
			$message = $this->Message->getOne($id);
			if (empty($message['reply_to'])) 
			{
				array_unshift($following['private_threaded'], $message['id']);
			}
			array_unshift($following['private'], $message['id']);							
		}
		rsort($following['private']);
		rsort($following['private_threaded']);
		return $this->save($following);
	}

	/**
	 * Add a friend request
	 *
	 * @access public
	 * @param array $followed Data of user to follow
	 * @param array $following Data of user following
	 * @return boolean
	 */
	public function addFriendRequest(&$followed, $following)
	{
		array_unshift($followed['friend_requests'], $following['id']);
		return $this->save($followed);
	}	
	
	/**
	 * Add to inbox
	 *
	 * @access public
	 * @param array $user 
	 * @param array $message_id 
	 * @return
	 */
	public function addToInbox(&$user, $message_id)
	{
		$this->addTo('inbox', $user, $message_id);
	}
	
	/**
	 * Add to sent
	 *
	 * @access public
	 * @param array $user 
	 * @param array $message_id
	 */
	public function addToSent(&$user, $message_id)
	{
		$this->addTo('sent', $user, $message_id);
	}

	/**
	 * Add to mentions
	 *
	 * @access public
	 * @param array $user_mention 
	 * @param array $message_id
	 */
	public function addToMentions(&$user_mention, $message_id)
	{
		$this->addTo('mentions', $user_mention, $message_id);
	}
	
	/**
	 * Add to private
	 *
	 * @access public
	 * @param array $user 
	 * @param array $message
	 */
	public function addToPrivate(&$user, $message)
	{
		if (!empty($message['id'])) 
		{
			$this->addTo('private', $user, $message['id']);
			if (empty($message['reply_to'])) 
			{
				$this->addTo('private_threaded', $user, $message['id']);
			}
		}		
	}
		
	/**
	 * Add message to public stream
	 *
	 * @access public
	 * @param array $user 
	 * @param array $message
	 */
	public function addToPublic(&$user, $message)
	{
		if (!empty($message['id'])) 
		{
			$this->addTo('public', $user, $message['id']);
		}
	}
	
	/**
	 * Add message to to public and private streams
	 *
	 * @access public
	 * @param array $user 
	 * @param array $message
	 */
	public function addToPublicAndPrivate(&$user, $message)
	{
		$this->addToPublic($user, $message);
		$this->addToPrivate($user, $message);
	}

	/**
	 * Add user to group
	 * 
	 * @access public
	 * @param array $user
	 * @param int $group_id
	 */
	public function addGroup(&$user, $group_id)
	{
		if (!is_array($user['groups']))
		{
			$user['groups'] = array();
		}
		if (!in_array($group_id, $user['groups']))
		{
			array_push($user['groups'], $group_id);
		}
		return $this->save($user, array('validate'=>false));
	}	

	/**
	 * Change the password of a user
	 *
	 * @access public
	 * @param integer $user_id
	 * @param string $password
	 */
	public function changePassword($user_id, $password)
	{
		$this->mode = 'change_password';
		$this->postData = $this->updateData($this->userData, $this->postData);
		$this->postData['id'] = $user_id;
		$this->postData['old_password'] = $password;
		$this->modelData = $this->postData;
		if ($this->validate()) 
		{
			$this->mode = null;
			unset($this->modelData['old_password']);
			unset($this->modelData['new_password']);
			unset($this->modelData['new_password_confirm']);
			unset($this->modelData['password_confirm']);						
			return $this->save($this->modelData);
		}
		else 
		{
			return false;
		}
	}

	/**
	 * Confirm Request
	 *
	 * @access public
	 * @param string $username of the user requesting to follow
	 * @param array $followed the user being followed
	 * @return boolean
	 */
	public function confirm($username, $followed)
	{
		$this->mode = 'confirm';
		$user = $this->getByUsername($username);	//get follower
		if (!$user) 
		{
			return false;
		}		
		if (in_array($user['id'], $followed['friend_requests']))	//check if follower in in requests
		{
			$this->startTransaction();
			if (!in_array($user['id'], $followed['followers'])) //check if follower is already following
			{
				$this->_follow($followed, $user);
			}
			$followed = $this->get($followed['id']);
			$followed['friend_requests'] = $this->removeFromArray($followed['friend_requests'], $user['id']);			
			$this->save($followed);
			return $this->endTransaction();
		} 
		else 
		{
			return false;
		}
	}

	/**
	 * Delete a user
	 *
	 * @access public
	 * @param array $data User Data
	 * @return boolean
	 */
	public function deleteMe($data = array())
	{
		if ($data) 
		{
			$this->delete($data['id']);
			$this->delete($data['username'], array('prefixValue'=>'username'));
			$this->delete($data['email'], array('prefixValue'=>'email'));			
			return true;
		} 
		else 
		{
			return false;
		}
	}

	/**
	 * Deny Request
	 *
	 * @access public
	 * @param string $username of the user requesting to follow
	 * @param array $followed the user being denied
	 * @return boolean
	 */
	public function deny($username, $followed)
	{
		$this->mode = 'deny';
		$user = $this->getByUsername($username);	//get follower
		if (!$user) 
		{
			return false;
		}		
		if (in_array($user['id'], $followed['friend_requests']))	//check if follower in in requests
		{
			$this->startTransaction();
			if (in_array($user['id'], $followed['friend_requests'])) //check if follower is already following
			{
				$followed['friend_requests'] = $this->removeFromArray($followed['friend_requests'], $user['id']);
			}
			$this->save($followed);
			return $this->endTransaction();
		} 
		else 
		{
			return false;
		}
	}
	
	/**
	 * Follow a user
	 *
	 * @access public
	 * @param array $followed Data of user to follow
	 * @param array $following Data of user following
	 * @return boolean
	 */
	public function follow($followed = null, $following)
	{
		if (empty($followed)) {
			return false;
		} elseif (is_string($followed)){
			$followed = $this->User->getByUsername($followed);
		}
		if (!empty($followed)) 
		{
			$this->startTransaction();
			if ($followed['locked']) 
			{
				$this->addFriendRequest($followed, $following);
			}
			else 
			{
				$this->_follow($followed, $following);
				$this->addFollowedMessages($followed, $following);
			}
			
			return $this->endTransaction();
		} 
		else 
		{
			return false;
		}
	}

	/**
	 * Unfollow a user
	 *
	 * @access public	
	 * @param string $username[optional] of user to follow
	 * @param array $user data of user following
	 * @return boolean
	 */
	public function unfollow($username = null, $following)
	{
		$this->startTransaction();
		$user = $this->getByUsername($username);
		if (empty($user)){
		 $this->endTransaction();
		 return array('message'=>"Oops! There was an error trying to unfollow that user. Please make sure you're not typing the URL manually.","type"=>"error");
		}
		$user['followers'] = $this->removeFromArray($user['followers'], $following['id']);
		$this->save($user);
		$following['following'] = $this->removeFromArray($following['following'], $user['id']);		
		$this->save($following);
		$this->endTransaction();
		return array('message'=>"You are no longer following " . $username,"type"=>"success");
	}
	
	/**
	 * Get list of followers for dms
	 *
	 * @access public
	 * @param array $followers
	 * @param object $Group	
	 * @return array key=>value pairings for select
	 */
	public function friendSelect($user, $Group)
	{
		$return = array();
		//add followers
		if ($user['followers']) 
		{
			$return[0]['Friends'] = array();
			foreach ($user['followers'] as $friend_id) {
				if (in_array($friend_id, $user['following'])):
					$data = $this->get($friend_id);
					if ($data) 
					{
						$return[0]['Friends'][$data['username']] = $data['username'];
					}
				endif;
			}
		}
		//Add groups
		$groups = $Group->getManyByIds($user['groups']);	//get many groups
		if (!$groups) //if no groups are returned, just return users
		{
			return $return;
		}
		//add dm to groups
		$return[1]['Teams'] = array();
		foreach ($groups as $g) {
			$return[1]['Teams']['!' . $g['name']] = $g['name'];
		}
		//add group members
		$i = 2;
		foreach ($groups as $g) {
			$return[$i][$g['name']] = array();
			$members = $Group->getMembers($g['members']);
			foreach ($members as $member) {
				if ($user['id'] != $member['id']) 
				{
					$return[$i][$g['name']][$member['username']] = $member['username'];
				}
			}
			$i++;
		}
		return $return;
	}
	
	/**
	 * Get a User's data by id
	 *
	 * @access public	
	 * @param integer $user_id[optional]
	 * @return 	array $user_data
	 */
	public function get($user_id = null)
    {
        if ($user_id)
        {
            return $this->find($user_id);
        }
        else
        {
            return null;
        }
    }

	/**
	 * Get many users by user_ids
	 *
	 * @access public	
	 * @param array $user_ids[optional]
	 * @return 	array $user_data
	 */
	public function getMany($user_ids = array(), $start = null, $end = null)
    {
		if (!is_array($user_ids)) 
		{
			return;
		}
		if (($start !== null) && ($end !== null))
		{
			$user_ids = array_slice($user_ids, $start, $end);	
		}		
		$return = array();
		foreach ($user_ids as $user_id) {
			$user = $this->get($user_id);
			if (!empty($user)) 
			{
				$return[] = $user;
			}
		}
		return $return;
    }

	/**
	 * Get a User's data by email
	 *
	 * @access public	
	 * @param integer $email[optional]
	 * @return 	array $user_data
	 */
	public function getByEmail($email = null)
	{
		$return = null;
		if ($email) 
		{
			$user_id = $this->find($email, array('prefixValue'=>'email'));
			if ($user_id) 
			{
				$return = $this->find($user_id);
			}
		}
		return $return;
	}

	/**
	 * Get a User's data by username
	 *
	 * @access public	
	 * @param integer $username[optional]
	 * @return 	array $user_data
	 */
	public function getByUsername($username = null)
	{
		$return = null;
		if ($username) 
		{
			$user_id = $this->find($username, array('prefixValue'=>'username'));
			if ($user_id) 
			{
				$return = $this->find($user_id);
			}
		}
		return $return;
	}

	/**
	 * Get all of a users friend requests
	 *
	 * @access public	
	 * @param integer $user_id
	 * @return 	
	 */
	public function getFriendRequests($requests)
    {
		$return = array();
		foreach ($requests as $request) {
			$return[] = $this->get($request);
		}
		return $return;
    }

	/**
	 * Get all of a users friend requests
	 *
	 * @access public	
	 * @param array $you The other user
	 * @param array $me The logged in user	
	 * @return string 'following,' 'pending,' 'follow'
	 */
	public function getFriendStatus($you, $me)
    {
		if ($this->isFollowing($you['id'], $me['following']))
		{
			return 'following';
		}
		elseif ($this->isPendingFriendRequest($you['friend_requests'], $me['id']))
		{
			return 'pending';
		}
		else
		{ 
			return 'follow';
		}
    }
   
    /**
     * Hash a password
     *
	 * @access public
     * @param array $password
     * @return string
     */
	public function hashPassword($password)
    {
        return $this->hash($password);
    }
   
    /**
     * Check if a user is following another user
     *
	 * @access public
     * @param integer $user_id The user in question
     * @param integer $my_id The user doing the asking
     * @return boolean
     */
	public function isFollowing($user_id, $following)
    {
		if (!empty($following)) {
			if (in_array($user_id, $following))
	        {
	            return true;
	        }
	        else
	        {
	            return false;
	        }
		} else {
			return false;
		}
    }

	/**
	 * Is a user locked
	 *
	 * @access public
	 * @param array $user in question
	 * @param array $otherUser
	 * @return boolean
	 */
	public function isLocked($user = array(), $otherUser = array())
	{
		if ($user['locked']) 
		{
			if (!empty($otherUser['id'])) 
			{
				if ($user['id'] == $otherUser['id']) 
				{
					return false;
				}
				return !in_array($otherUser['id'], $user['followers']);
			}
			return true;
		}
		return false;
	}
	

    /**
     * Check if a user has already requested friend
     *
	 * @access public
     * @param integer $user_id The user in question
     * @param integer $my_id The user doing the asking
     * @return boolean
     */
	public function isPendingFriendRequest($friendRequests, $my_id)
    {
		if (!empty($friendRequests)) {
			if (in_array($my_id, $friendRequests))
	        {
	            return true;
	        }
	        else
	        {
	            return false;
	        }
		} else {
			return false;
		}
    }

	/**
	 * Does password match on on record
	 *
	 * @access public
	 * @return 
	 */
	public function passwordMatches()
	{
		if (($this->hash($this->modelData['password'])) == ($this->modelData['old_password'])) 
		{
			return true;
		}
		else 
		{
			return false;
		}
	}

	/**
	 * Does the password and its confirm work?
	 *
	 * @access public
	 * @return boolean
	 */
	public function passwordsMatch()
	{
		if ((isset($this->modelData['password'])) && (isset($this->modelData['passwordconfirm'])))
		{
			if ($this->modelData['password'] == $this->modelData['passwordconfirm']) 
			{
				return true;
			} 
			else 
			{
				return false;
			}		
		} 
		else 
		{
			return false;
		}
	}
  
	/**
	 * Does the password and the username NOT match
	 *
	 * @access public
	 * @return boolean (Note: return true if they do NOT match)
	 */
	public function passwordUsernameDoNotMatch()
	{
		if ((isset($this->modelData['username'])) && (isset($this->modelData['password'])))  
		{
			if ($this->modelData['username'] == $this->modelData['password'])
			{
				return false;
			} 
			else 
			{
				return true;
			}
		} 
		else 
		{
			return true;
		}
	}

	/**
	 * Remove a group, by id, from a user's groups 
	 *  
	 * @access public	
	 * @param int $user
	 * @param integer $group_id
	 * @return boolean
	 */
	public function removeGroup($user_id = null, $group_id = null)
	{
		$user = $this->User->get($user_id);
		if ((empty($user)) || (!$group_id)) 
		{
			return false;
		}
		$user['groups'] = $this->removeFromArray($user['groups'], $group_id);
		return $this->save($user);
	}

	/**
	 * Reset Password
	 *
	 * @access public
	 * @param array $user
	 * @param array $data New password data	
	 * @return 
	 */
	public function resetPassword(&$user, $data)
	{
		$this->mode = 'reset_password';
		$this->modelData = $data;
		if ($this->validate()) 
		{
			$this->mode = null;
			$user['password'] = $this->modelData['password'];
			unset($this->modelData['passwordconfirm']);						
			return $this->save($user);
		}
		else 
		{
			return false;
		}
	}	

	/**
	 * Save an email alias
	 *
	 * @access public
	 * @param array $user
	 * @return boolean
	 */
	public function saveEmailAlias($user = array())
	{
		return $this->save($user, array('prefixValue'=>'email', 'saveOnly'=>'id', 'validate'=>false));
	}
	
	/**
	 * Save an email alias
	 *
	 * @access public
	 * @param array $user
	 * @return boolean
	 */
	public function saveUsernameAlias($user = array())
	{
		return $this->save($user, array('prefixValue'=>'username', 'saveOnly'=>'id', 'validate'=>false));
	}
	
    /**
     * Send messages to followes
     *
	 * @access public
     * @param array $message
	 * @param array $followers An array of follower ids
     * @return boolean
     */
	public function sendToFollowers($message, $followers)
    {
		if (!empty($message['id'])) 
		{
			foreach ($followers as $follower_id)
	        {
				$follower = $this->get($follower_id);			
				$this->addToPrivate($follower, $message);
	        }
		}
    }

    /**
     * Sign in a user
     *
	 * @access public
     * @param array $data Userdata
     * @return mixed Array on success | boolean false on failure
     */
	public function signIn($data)
    {
        if (! empty($data['username']))
        {
            
			$user = (stristr($data['username'],"@"))? $this->getByEmaiL($data['username']) : $this->getByUsername($data['username']);
			
            if ((! empty($user)) && ($this->hashPassword($data['password']) == $user['password']))
            {
                return $user;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Create a new user
     *
     * @todo Move create and modified to parent
     * @todo Make return reflect actual save and transactional
     * @access public
     * @param array $data Data to save
     * @param string $timeZone
     */
	public function signUp($data = array(), $permission = null)
    {
        $user = array();
        $now = time();
        $this->mode = 'signup';
		$user = $this->create($data);
		if ($permission) 
		{
			$user['permission'] = $permission;
		}		
		if (!empty($this->postData['realname'])) 
		{
			$this->postData['realname'] = strip_tags($this->postData['realname']);
		}
		$user['id'] = $this->makeId($this->idGenerator);
        $user['time_zone'] = $this->defaultTimeZone;
		$this->startTransaction();
		if ($this->save($user)) 
		{	
			$this->insertId = (!empty($data['id']))?$data['id']:$user['id'];
			$this->saveEmailAlias($user);
			$this->saveUsernameAlias($user);
		}
		return $this->endTransaction();
    }

	/**
	 * SMS a user
	 *
	 * @access public
	 * @param integer $id
	 * @return boolean
	 */
	public function sms($to = array(), $from = array(), $message = null, $group = null)
	{
		if (!empty($this->mail)) 
		{
			unset($this->mail);
		}
		if ($group) 
		{
			$subject = $from['username'] . ' sent you a message for ' . $group;
		}
		else 
		{
			$subject = $from['username'] . ' sent you a message on Teamitup.';
		}
		$this->load->library('Mail');				
		if ($to['device_updates'] && $to['phone']  && $to['carrier'])
		{
			if ($group) 
			{
				$sms_message = $from['username'] .' to ' . $group . ': ' . $message;
			}
			else 
			{
				$sms_message = $from['username'] . ': ' . $message;				
			}
			$this->mail->sms($to['phone'] . $to['carrier'], $from['username'], $sms_message, $to['sms_activated'], null, null);
		}
		$this->mail->dmEmail($to, $message, $subject);
		return true;
	}
    
	/**
	 * Activate an sms
	 *
	 * @access public
	 * @param integer $key
	 * @param array $user	
	 * @return boolean
	 */
	public function smsKey($key = null, &$user = array())
	{
		if ((!$key) || (!$user)) 
		{
			return false;
		}
		$sms_key = $this->Sms_key->find($user['id']);
		if (empty($sms_key)) 
		{
			return false;
		}
		if ($sms_key['key'] != $key) 
		{
			return false;
		}
		$this->Sms_key->delete($user['id']);
		$user['sms_activated'] = true;
		if (isset($user['key'])) 
		{
			unset($user['key']);
		}
		return $this->save($user);
	}
	

	/**
	 * Is a user's sms pending activation?
	 *
	 * @access public
	 * @param array $user
	 * @return boolean
	 */
	public function smsPending($user)
	{
		if(!$user['sms_activated'] && $user['device_updates']) 
		{
			return true;
		}
		else 
		{
			return false;
		}
	}

	/**
	 * Update read messages count
	 *
	 * @access public
	 * @param string $counter Name of counter (e.g. 'private', 'inbox')	
	 * @param array $user User data, passed by reference
	 * @return boolean
	 */
	public function updateRead($counter = null, &$user = array())
	{
		$read_counter = $counter . '_read';
		if (!array_key_exists($counter, $user) || !array_key_exists($read_counter, $user)) 
		{
			return false;
		}
		if ($user[$read_counter] != count($user[$counter])) 
		{
			$user[$read_counter] = count($user[$counter]);
			return $this->save($user);
		}
		return true;
	}
	
	/**
	 * Update read messages count
	 *
	 * @access public
	 * @param array $user User data, passed by reference
	 * @param array $group Group data
	 * @return boolean
	 */
	public function updateReadGroup(&$user = array(), $group = array())
	{
		if (empty($user)) return false;
		$counter_array = 'group_messages_read';
		$group_name = $group['name'];
		$message_count = count($group['messages']);		
		if (!array_key_exists($counter_array, $user)) 
		{
			return false;
		}
		if (array_key_exists($group_name, $user[$counter_array]))
		{
			if ($user[$counter_array][$group_name] == $message_count) 
			{
				return true;
			}
		}
		$user[$counter_array][$group_name] = $message_count;
		return $this->save($user);
	}
	

	/**
	 * Update a user's profile
	 *
	 * @access public	
	 * @param integer $user_id
	 * @return boolean
	 */
	public function updateProfile($user_id)
	{
		$this->mode = 'profile';		
		$this->postData = $this->updateData($this->userData, $this->postData);
		$this->postData['id'] = $user_id;
		$this->startTransaction();
		if ($this->save($this->postData)) 
		{
			$this->delete($this->userData['username'], array('prefixValue'=>'username'));
			$this->delete($this->userData['email'], array('prefixValue'=>'email'));
			$this->saveEmailAlias($this->postData);
			$this->saveUsernameAlias($this->postData);
		}
		return $this->endTransaction();
	}

	/**
	 * Update the sms data for a user
	 * 
	 * @access public	
	 * @param array $user
	 * @param object $smsKey	
	 * @return boolean
	 */
	public function updateSms($user)
	{
		$this->mode = 'sms';
		if (!$this->postData['device_updates']) 
		{
			$this->postData['phone'] = null;
			$this->postData['carrier'] = null;			
			$this->postData['sms_activated'] = false;
		}				
		$user = $this->updateData($user, $this->postData);
		$this->startTransaction();
		if ($this->save($user)) 
		{
			if ($this->postData['phone'])
			{
				$this->Sms_key->code = $this->randomNum(6);
				$key = $this->Sms_key->create();
				$key['user_id'] = $user['id'];
				$key['key'] = $this->Sms_key->code;
			}
			$this->Sms_key->save($key);
			return $this->endTransaction();
		} 
		else 
		{
			return false;
		}
	}
	
	/**
	 * Update threading preference
	 * 
	 * @access public	
	 * @param integer $user_id
	 * @param string $setting
	 * @return boolean
	 */
	public function updateThreading($user_id,$setting)
	{
		$data['threading'] = $setting;
		$data['id'] = $user_id;
		$data['modified'] = time();
		$data = $this->updateData($this->userData, $data);
		$this->startTransaction();
		$this->save($data);
		return $this->endTransaction();
	}

	
	/**
	 * Validates a user
	 *
	 * @access public	
	 * @return boolean
	 */	
	public function validate()
	{
		if (($this->mode == 'signup') OR  ($this->mode == 'profile'))
		{
			$this->setAction();			
			$this->validates_format_of('email', array('with'=>VALID_EMAIL, 'message'=>'A valid email is required.'));
			$this->validates_uniqueness_of('email', array('message'=>'Email is already in use.'));
			$this->validates_presence_of('email', array('message'=>'A valid email is required.'));
			$this->validates_callback('isNotReserved', 'username', array('message'=>'This is a reserved screenname.'));
			$this->validates_length_of('username', array('min'=>1, 'max'=>15, 'message'=>'A screenname must be between 1 and 15 characters long.'));
			$this->validates_uniqueness_of('username', array('message'=>'Username has already been taken.'));
			$this->validates_format_of('username', array('with'=>ALPHANUM, 'message'=>'A screenname may only contain letters (A-Za-z), numbers (0-9), and underscores (_). No spaces allowed.'));
			$this->validates_presence_of('username', array('message'=>'A username is required.'));
			$this->validates_length_of('realname', array('min'=>1, 'max'=>50, 'message'=>'Full name must be between 1 and 50 characters long.'));
			$this->validates_presence_of('realname', array('message'=>'Full name is required.'));
		}
		if ($this->mode == 'change_password') 
		{
			$this->validates_callback('passwordMatches', 'old_password', array('message'=>'Your password does not match the one on record.'));
			$this->modelData['password'] = $this->modelData['new_password'];
			$this->modelData['passwordconfirm'] = $this->modelData['new_password_confirm'];
		}		
		if (($this->mode == 'signup') || ($this->mode == 'change_password') || ($this->mode == 'reset_password'))
		{
			$this->validates_length_of('password', array('min'=>6, 'max'=>25, 'message'=>'A password must be between 6 and 25 characters long.'));
			$this->validates_callback('passwordUsernameDoNotMatch', 'password', array('message'=>'Your password cannot be the same as your username.'));
			$this->validates_callback('passwordsMatch', 'password', array('message'=>'Your password and the confirmation do not match.'));
			$this->validates_format_of('password', array('with'=>ALPHANUM, 'message'=>'A password may only contain letters (A-Za-z), numbers (0-9), and underscores (_). No spaces allowed.'));
			$this->validates_presence_of('password', array('message'=>'A password is required.'));
			$this->modelData['password'] = $this->hashPassword($this->modelData['password']);	//has to be here in order not to screw up character counts and matching
		}
		if ($this->mode == 'profile') 
		{
			$this->validates_callback('isTimeZone', 'time_zone', array('message'=>'You must select a time zone from the list.'));
			$this->validates_length_of('about_me', array('min'=>0, 'max'=>160, 'message'=>'Bio must be fewer than 160 characters.'));
			$this->validates_length_of('hometown', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));	
			$this->validates_length_of('birthdate', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('height', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('weight', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('handed_footed', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('athletic', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('academics', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('about_me', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('favorite_sports', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('favorite_teams', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('favorite_players', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));	
			$this->validates_length_of('phone', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('im', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('address', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('address_line2', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('state', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('city', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('postal_code', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('country', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));		
			$this->validates_length_of('college', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('degree', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('high_school', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('employer', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('position', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('employment_description', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));	
			$this->validates_length_of('employment_location', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
			$this->validates_length_of('employment_time_period', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters.'));
		}
		if ($this->mode == 'sms') 
		{
			if ($this->modelData['device_updates'] != 0) 
			{
				$this->validates_numericality_of('phone', array('message'=>'A phone number can only be made up of numbers.'));				
				$this->validates_presence_of('phone', array('message'=>'A phone is required.'));
				$this->validates_presence_of('carrier', array('message'=>'A carrier is required.'));				
			}
		}
		if (!empty($this->modelData['passwordconfirm'])) 
		{
			$this->modelData['passwordconfirm'] = null;
		}
	    return (count($this->validationErrors) == 0);
	}
	
}
?>