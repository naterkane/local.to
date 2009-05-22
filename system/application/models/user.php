<?php
/**
 * User Model
 */
class User extends App_Model
{

	protected $idGenerator = 'userId';
	public $defaultTimeZone = 'US/Eastern';
	public $timeZones = array('US/Hawaii'=>'(GMT-10:00) Hawaii','US/Alaska'=>'(GMT-09:00) Alaska','US/Pacific' => '(GMT-07:00) Pacific Time (US &amp; Canada)','US/Arizona'=>'(GMT-07:00) Arizona','US/Mountain'=>'(GMT-07:00) Mountain Time (US &amp; Canada)','US/Central'=>'(GMT-06:00) Central Time (US &amp; Canada)','US/Eastern'=>'(GMT-05:00) Eastern Time (US &amp; Canada)','US/East-Indiana' => '(GMT-05:00) Indiana (East)','America/Tijuana'=>'(GMT-08:00) Tijuana','America/Chihuahua'=>'(GMT-07:00) Chihuahua','America/Mazatlan'=>'(GMT-07:00) Mazatlan','America/Monterrey'=>'(GMT-06:00) Monterrey','America/Mexico_City'=>'(GMT-06:00) Mexico City',		'Canada/East-Saskatchewan'=>'(GMT-06:00) Saskatchewan','Canada/Saskatchewan'=>'(GMT-06:00) Saskatchewan','America/Bogota'=>'(GMT-05:00) Bogota','America/Lima'=>'(GMT-05:00) Lima','America/Caracas'=>'(GMT-04:00) Caracas','America/Santiago'=>'(GMT-04:00) Santiago','Canada/Newfoundland'=>'(GMT-03:30) Newfoundland','Atlantic/Azores'=>'(GMT-01:00) Azores','Africa/Casablanca'=>'(GMT) Casablanca','Europe/Dublin'=>'(GMT) Dublin','Europe/Lisbon'=>'(GMT) Lisbon','Europe/London'=>'(GMT) London','Africa/Monrovia'=>'(GMT) Monrovia','Europe/Amsterdam'=>'(GMT+01:00) Amsterdam','Europe/Belgrade'=>'(GMT+01:00) Belgrade','Europe/Berlin'=>'(GMT+01:00) Berlin','Europe/Bratislava'=>'(GMT+01:00) Bratislava','Europe/Brussels'=>'(GMT+01:00) Brussels','Europe/Budapest'=>'(GMT+01:00) Budapest','Europe/Copenhagen'=>'(GMT+01:00) Copenhagen','Europe/Ljubljana'=>'(GMT+01:00) Ljubljana','Europe/Madrid'=>'(GMT+01:00) Madrid','Europe/Paris'=>'(GMT+01:00) Paris','Europe/Prague'=>'(GMT+01:00) Prague','Europe/Rome'=>'(GMT+01:00) Rome','Europe/Sarajevo'=>'(GMT+01:00) Sarajevo','Europe/Skopje'=>'(GMT+01:00) Skopje','Europe/Stockholm'=>'(GMT+01:00) Stockholm','Europe/Vienna'=>'(GMT+01:00) Vienna','Europe/Warsaw'=>'(GMT+01:00) Warsaw','Europe/Zagreb'=>'(GMT+01:00) Zagreb','Europe/Athens'=>'(GMT+02:00) Athens','Europe/Bucharest'=>'(GMT+02:00) Bucharest','Africa/Cairo'=>'(GMT+02:00) Cairo','Africa/Harare'=>'(GMT+02:00) Harare','Europe/Helsinki'=>'(GMT+02:00) Helsinki','Asia/Istanbul'=>'(GMT+02:00) Istanbul','Europe/Istanbul'=>'(GMT+02:00) Istanbul','Asia/Jerusalem'=>'(GMT+02:00) Jerusalem','Europe/Minsk'=>'(GMT+02:00) Minsk','Europe/Riga'=>'(GMT+02:00) Riga','Europe/Sofia'=>'(GMT+02:00) Sofia','Europe/Tallinn'=>'(GMT+02:00) Tallinn','Europe/Vilnius'=>'(GMT+02:00) Vilnius','Asia/Baghdad'=>'(GMT+03:00) Baghdad','Asia/Kuwait'=>'(GMT+03:00) Kuwait','Europe/Moscow'=>'(GMT+03:00) Moscow','Africa/Nairobi'=>'(GMT+03:00) Nairobi','Asia/Riyadh'=>'(GMT+03:00) Riyadh','Europe/Volgograd'=>'(GMT+03:00) Volgograd','Asia/Tehran'=>'(GMT+03:30) Tehran','Asia/Baku'=>'(GMT+04:00) Baku','Asia/Muscat'=>'(GMT+04:00) Muscat','Asia/Tbilisi'=>'(GMT+04:00) Tbilisi','Asia/Yerevan'=>'(GMT+04:00) Yerevan','Asia/Kabul'=>'(GMT+04:30) Kabul','Asia/Karachi'=>'(GMT+05:00) Karachi','Asia/Tashkent'=>'(GMT+05:00) Tashkent','Asia/Almaty'=>'(GMT+06:00) Almaty','Asia/Dhaka'=>'(GMT+06:00) Dhaka','Asia/Novosibirsk'=>'(GMT+06:00) Novosibirsk','Asia/Rangoon'=>'(GMT+06:30) Rangoon','Asia/Bangkok'=>'(GMT+07:00) Bangkok','Asia/Jakarta'=>'(GMT+07:00) Jakarta','Asia/Krasnoyarsk'=>'(GMT+07:00) Krasnoyarsk','Asia/Chongqing'=>'(GMT+08:00) Chongqing','Asia/Irkutsk'=>'(GMT+08:00) Irkutsk','Australia/Perth'=>'(GMT+08:00) Perth','Asia/Singapore'=>'(GMT+08:00) Singapore','Singapore'=>'(GMT+08:00) Singapore','Asia/Taipei'=>'(GMT+08:00) Taipei','Asia/Urumqi'=>'(GMT+08:00) Urumqi','Asia/Seoul'=>'(GMT+09:00) Seoul','Asia/Tokyo'=>'(GMT+09:00) Tokyo','Asia/Yakutsk'=>'(GMT+09:00) Yakutsk','Australia/Adelaide'=>'(GMT+09:30) Adelaide','Australia/Darwin'=>'(GMT+09:30) Darwin','Australia/Brisbane'=>'(GMT+10:00) Brisbane','Australia/Canberra'=>'(GMT+10:00) Canberra','Pacific/Guam'=>'(GMT+10:00) Guam','Australia/Hobart'=>'(GMT+10:00) Hobart','Australia/Melbourne'=>'(GMT+10:00) Melbourne','Australia/Sydney'=>'(GMT+10:00) Sydney','Asia/Vladivostok'=>'(GMT+10:00) Vladivostok','Asia/Magadan'=>'(GMT+11:00) Magadan','Pacific/Auckland'=>'(GMT+12:00) Auckland','Pacific/Fiji'=>'(GMT+12:00) Fiji','Asia/Kamchatka'=>'(GMT+12:00) Kamchatka');
		
	/**
	 * Private method for following
	 *
	 * @access private
	 * @param array $followed Data of user to follow
	 * @param array $following Data of user following
	 * @return 
	 */
	private function _follow($followed, $following)
	{
		array_unshift($followed['followers'], $following['id']);
		$this->save($this->prefixUser($followed['id']), $followed);
		array_unshift($following['following'], $followed['id']);
		$this->save($this->prefixUser($following['id']), $following);		
		return true;
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
		return $this->save($this->prefixUser($followed['id']), $followed);
	}	
	
	/**
	 * Add to inbox
	 *
	 * @access public
	 * @param array $user 
	 * @param array $message_id 
	 * @return array $user
	 */
	public function addToInbox(&$user, $message_id)
	{
		$this->addTo('inbox', 'prefixUser', $user, $message_id);
	}
	
	/**
	 * Add to sent
	 *
	 * @access public
	 * @param array $user 
	 * @param array $message_id 
	 * @return array $user
	 */
	public function addToSent(&$user, $message_id)
	{
		$this->addTo('sent', 'prefixUser', $user, $message_id);
	}
	
	/**
	 * Add to private
	 *
	 * @access public
	 * @param array $user 
	 * @param array $message_id 
	 * @return array $user
	 */
	public function addToPrivate(&$user, $message_id)
	{
		$this->addTo('private', 'prefixUser', $user, $message_id);
	}
		
	/**
	 * Add to public
	 *
	 * @access public
	 * @param array $user 
	 * @param array $message_id 
	 * @return array $user
	 */
	public function addToPublic(&$user, $message_id)
	{
		$this->addTo('public', 'prefixUser', $user, $message_id);
	}
	
	/**
	 * Add to public and private
	 *
	 * @access public
	 * @param array $user 
	 * @param array $message_id 
	 * @return array $user
	 */
	public function addToPublicAndPrivate(&$user, $message_id)
	{
		$this->addToPublic($user, $message_id);
		$this->addToPrivate($user, $message_id);
	}

	public function addGroup($user_id,$group_id)
	{
		$data = $this->userData;
		if (!is_array($data['groups']))
		{
			$data['groups'] = array();
		}
		if (!in_array($group_id,$data['groups']))
		{
			array_push($data['groups'],$group_id);
		}	
		$data['id'] = $user_id;
		$data['modified'] = time();
		$data = $this->updateData($this->userData, $data);
		$this->startTransaction();
		$this->save($this->prefixUser($data['id']), $data);
		return $this->endTransaction();
	}	

	/**
	 * Change password
	 *
	 * @access public
	 * @param int $user_id
	 * @param string $password	
	 * @return 
	 */
	function changePassword($user_id, $password)
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
			return $this->save($this->prefixUser($this->modelData['id']), $this->modelData);
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
	function confirm($username, $followed)
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
			$this->save($this->prefixUser($followed['id']), $followed);
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
	function deleteMe($data = array())
	{
		if ($data) 
		{
			$this->delete($this->prefixUser($data['id']));
			$this->delete($this->prefixUsername($data['username']));
			$this->delete($this->prefixUserEmail($data['email']));
			return true;
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
	function follow($followed, $following)
	{
		if ($followed) 
		{
			$this->startTransaction();
			if ($followed['locked']) 
			{
				$this->addFriendRequest($followed, $following);
			}
			else 
			{
				$this->_follow($followed, $following);
			}
			return $this->endTransaction();;			
		} 
		else 
		{
			return false;
		}
	}
	
	/**
	 * Get list of followers for dms
	 *
	 * @access public
	 * @param array $followers
	 * @return array key=>value pairings for select
	 */
	public function friendSelect($friends = array())
	{
		$return = array();
		foreach ($friends as $friend) {
			$data = $this->get($friend);
			if ($data) 
			{
				$return[$data['username']] = $data['username'];
			}
		}
		return $return;
	}
	
	/**
	 * Get a User's data by id
	 *
	 * @param int $user_id[optional]
	 * @return 	array $user_data
	 */
    function get($user_id = null)
    {
        if ($user_id)
        {
            return $this->find($this->prefixUser($user_id));
        }
        else
        {
            return null;
        }
    }

	/**
	 * Get a User's data by username
	 *
	 * @param int $username[optional]
	 * @return 	array $user_data
	 */
	function getByUsername($username = null)
	{
		$return = null;
		if ($username) 
		{
			$user_id = $this->find($this->prefixUsername($username));
			if ($user_id) 
			{
				$return = $this->find($this->prefixUser($user_id));
			}
		}
		return $return;
	}

	/**
	 * Get all of a users friend requests
	 *
	 * @param int $user_id
	 * @return 	
	 */
    function getFriendRequests($requests)
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
	 * @param array $you The other user
	 * @param array $me The logged in user	
	 * @return string 'following,' 'pending,' 'follow'
	 */
    function getFriendStatus($you, $me)
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
     * @param array $password
     * @return string
     */
    function hashPassword($password)
    {
        return $this->hash($password);
    }
   
    /**
     * Check if a user is following another user
     *
     * @param int $user_id The user in question
     * @param int $my_id The user doing the asking
     * @return boolean
     */
    function isFollowing($user_id, $following)
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
     * Check if a user has already requested friend
     *
     * @param int $user_id The user in question
     * @param int $my_id The user doing the asking
     * @return boolean
     */
    function isPendingFriendRequest($friendRequests, $my_id)
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
	function passwordMatches()
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
	function passwordsMatch()
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
	function passwordUsernameDoNotMatch()
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
     * Send messages to followes
     *
     * @param int $message_id     
	 * @param array $followers An array of follower ids
     * @return boolean
     */
    function sendToFollowers($message_id, $followers)
    {
		$this->startTransaction();	
		foreach ($followers as $follower_id)
        {
			$follower = $this->get($follower_id);			
			array_unshift($follower['private'], $message_id);
            $this->save($this->prefixUser($follower['id']), $follower);
        }
        return $this->endTransaction();
    }

    /**
     * Sign in a user
     *
     * @param array $data Userdata
     * @return mixed Array on success | boolean false on failure
     */
    function signIn($data)
    {
        if (! empty($data['username']))
        {
            $user = $this->getByUsername($data['username']);
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
    function signUp($data = array())
    {
        $user = array();
        $now = time();
        $this->mode = 'signup';
		$data['id'] = $this->makeId($this->idGenerator);
        $data['locked'] = 0;
        $data['activated'] = 1;
		$data['threading'] = 0;
        $data['created'] = $now;
        $data['modified'] = $now;
        $data['time_zone'] = $this->defaultTimeZone;
		$data['followers'] = array();
		$data['following'] = array();
		$data['friend_requests'] = array();
		$data['public'] = array();
		$data['private'] = array();	
		$data['inbox'] = array();
		$data['sent'] = array();
		$this->startTransaction();
		$this->save($this->prefixUser($data['id']), $data);
		$this->join('UserEmail', $data['email'], $data);
		$this->join('Username', $data['username'], $data);	
		return $this->endTransaction();
    }

	/**
	 * Update threding preference
	 * 
	 * @return 
	 * @param integer $user_id
	 * @param string $setting
	 */
	function updateThreading($user_id,$setting)
	{
		$data['threading'] = $setting;
		$data['id'] = $user_id;
		$data['modified'] = time();
		$data = $this->updateData($this->userData, $data);
		$this->startTransaction();
		$this->save($this->prefixUser($data['id']), $data);
		return $this->endTransaction();
	}

	function removeGroup($user_id,$group_id)
	{
		$data = $this->userData;
		if (!is_array($data['groups']))
		{
			$data['groups'] = array();
		}
		if ($key = array_search($group_id,$data['groups']) && $key > 0)
		{
			unset($data['groups'][$key]);
			$data['groups'] = array_values($data['groups']);
		}
			
		$data['id'] = $user_id;
		$data['modified'] = time();
		$data = $this->updateData($this->userData, $data);
		$this->startTransaction();
		$this->save($this->prefixUser($data['id']), $data);
		return $this->endTransaction();
	}

	/**
	 * Update profile
	 *
	 * @access public
	 * @param int $group_id
	 * @return boolean
	 */
	function updateProfile($user_id)
	{
		$this->mode = 'profile';		
		$this->postData = $this->updateData($this->userData, $this->postData);
		$this->postData['id'] = $user_id;
		$this->startTransaction();
		$this->save($this->prefixUser($this->postData['id']), $this->postData);
		$this->delete($this->prefixUsername($this->userData['username']));
		$this->delete($this->prefixUserEmail($this->userData['email']));
		$this->mode = 'join';
		$this->join('UserEmail', $this->postData['email'], $this->userData);
		$this->join('Username', $this->postData['username'], $this->userData);
		return $this->endTransaction();
	}

	/**
	 * Unfollow a user
	 *
	 * @access public
	 * @param string $username of user to follow
	 * @param array $user data of user following
	 * @return boolean
	 */
	function unfollow($username = null, $following)
	{
		$this->startTransaction();
		$user = $this->getByUsername($username);
		$user['followers'] = $this->removeFromArray($user['followers'], $following['id']);
		echo $this->save($this->prefixUser($user['id']), $user);
		$following['following'] = $this->removeFromArray($following['following'], $user['id']);		
		echo $this->save($this->prefixUser($following['id']), $following);
		return $this->endTransaction();
	}
	
	/**
	 * Validates a user
	 *
	 * @access public
	 * @return boolean
	 */	
	function validate()
	{
		if (($this->mode == 'signup') OR  ($this->mode == 'profile'))
		{
			$this->setAction();
			$this->validates_format_of('email', array('with'=>VALID_EMAIL, 'message'=>'A valid email is required'));
			$this->validates_uniqueness_of('email', array('message'=>'Email is already in use', 'fieldValue'=>$this->prefixUserEmail($this->input->post('email'))));
			$this->validates_presence_of('email', array('message'=>'A valid email is required'));
			$this->validates_callback('isNotReserved', 'username', array('message'=>'This is a reserved username'));
			$this->validates_length_of('username', array('min'=>1, 'max'=>15, 'message'=>'A username must be between 1 and 15 characters long'));
			$this->validates_uniqueness_of('username', array('message'=>'Username has already been taken', 'fieldValue'=>$this->prefixUsername($this->input->post('username'))));
			$this->validates_format_of('username', array('with'=>ALPHANUM, 'message'=>'A username may only be made up of numbers, letters, and underscores'));
			$this->validates_presence_of('username', array('message'=>'A username is required'));
		}
		if ($this->mode == 'change_password') 
		{
			$this->validates_callback('passwordMatches', 'old_password', array('message'=>'Your password password does not match the one on record'));			
			$this->modelData['password'] = $this->modelData['new_password'];
			$this->modelData['passwordconfirm'] = $this->modelData['new_password_confirm'];
		}		
		if (($this->mode == 'signup') || ($this->mode == 'change_password'))
		{
			$this->validates_length_of('password', array('min'=>6, 'max'=>25, 'message'=>'A password must be between 6 and 25 characters long'));
			$this->validates_callback('passwordUsernameDoNotMatch', 'password', array('message'=>'Your password cannot be the same as your username'));
			$this->validates_callback('passwordsMatch', 'password', array('message'=>'Your password and the confirmation do not match'));
			$this->validates_format_of('password', array('with'=>ALPHANUM, 'message'=>'A password may only be made up of numbers, letters, and underscores'));
			$this->validates_presence_of('password', array('message'=>'A password is required'));
			$this->modelData['password'] = $this->hashPassword($this->modelData['password']);	//has to be here in order not to screw up character counts and matching
		}
		if ($this->mode == 'profile') 
		{
			$this->validates_callback('isTimeZone', 'time_zone', array('message'=>'You must select a time zone from the list'));
			$this->validates_length_of('bio', array('min'=>0, 'max'=>160, 'message'=>'A bio must be between 1 and 160 characters long'));
		}
		if ($this->mode == 'join') 
		{
			$this->validates_join();
		}
	    return (count($this->validationErrors) == 0);
	}
	

}

?>