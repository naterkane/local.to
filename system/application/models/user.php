<?php
/**
 * User Model
 */
class User extends App_Model
{

	var $defaultTimeZone = 'US/Eastern';
	var $timeZones = array('US/Hawaii'=>'(GMT-10:00) Hawaii','US/Alaska'=>'(GMT-09:00) Alaska','US/Pacific' => '(GMT-07:00) Pacific Time (US &amp; Canada)','US/Arizona'=>'(GMT-07:00) Arizona','US/Mountain'=>'(GMT-07:00) Mountain Time (US &amp; Canada)','US/Central'=>'(GMT-06:00) Central Time (US &amp; Canada)','US/Eastern'=>'(GMT-05:00) Eastern Time (US &amp; Canada)','US/East-Indiana' => '(GMT-05:00) Indiana (East)','America/Tijuana'=>'(GMT-08:00) Tijuana','America/Chihuahua'=>'(GMT-07:00) Chihuahua','America/Mazatlan'=>'(GMT-07:00) Mazatlan','America/Monterrey'=>'(GMT-06:00) Monterrey','America/Mexico_City'=>'(GMT-06:00) Mexico City',		'Canada/East-Saskatchewan'=>'(GMT-06:00) Saskatchewan','Canada/Saskatchewan'=>'(GMT-06:00) Saskatchewan','America/Bogota'=>'(GMT-05:00) Bogota','America/Lima'=>'(GMT-05:00) Lima','America/Caracas'=>'(GMT-04:00) Caracas','America/Santiago'=>'(GMT-04:00) Santiago','Canada/Newfoundland'=>'(GMT-03:30) Newfoundland','Atlantic/Azores'=>'(GMT-01:00) Azores','Africa/Casablanca'=>'(GMT) Casablanca','Europe/Dublin'=>'(GMT) Dublin','Europe/Lisbon'=>'(GMT) Lisbon','Europe/London'=>'(GMT) London','Africa/Monrovia'=>'(GMT) Monrovia','Europe/Amsterdam'=>'(GMT+01:00) Amsterdam','Europe/Belgrade'=>'(GMT+01:00) Belgrade','Europe/Berlin'=>'(GMT+01:00) Berlin','Europe/Bratislava'=>'(GMT+01:00) Bratislava','Europe/Brussels'=>'(GMT+01:00) Brussels','Europe/Budapest'=>'(GMT+01:00) Budapest','Europe/Copenhagen'=>'(GMT+01:00) Copenhagen','Europe/Ljubljana'=>'(GMT+01:00) Ljubljana','Europe/Madrid'=>'(GMT+01:00) Madrid','Europe/Paris'=>'(GMT+01:00) Paris','Europe/Prague'=>'(GMT+01:00) Prague','Europe/Rome'=>'(GMT+01:00) Rome','Europe/Sarajevo'=>'(GMT+01:00) Sarajevo','Europe/Skopje'=>'(GMT+01:00) Skopje','Europe/Stockholm'=>'(GMT+01:00) Stockholm','Europe/Vienna'=>'(GMT+01:00) Vienna','Europe/Warsaw'=>'(GMT+01:00) Warsaw','Europe/Zagreb'=>'(GMT+01:00) Zagreb','Europe/Athens'=>'(GMT+02:00) Athens','Europe/Bucharest'=>'(GMT+02:00) Bucharest','Africa/Cairo'=>'(GMT+02:00) Cairo','Africa/Harare'=>'(GMT+02:00) Harare','Europe/Helsinki'=>'(GMT+02:00) Helsinki','Asia/Istanbul'=>'(GMT+02:00) Istanbul','Europe/Istanbul'=>'(GMT+02:00) Istanbul','Asia/Jerusalem'=>'(GMT+02:00) Jerusalem','Europe/Minsk'=>'(GMT+02:00) Minsk','Europe/Riga'=>'(GMT+02:00) Riga','Europe/Sofia'=>'(GMT+02:00) Sofia','Europe/Tallinn'=>'(GMT+02:00) Tallinn','Europe/Vilnius'=>'(GMT+02:00) Vilnius','Asia/Baghdad'=>'(GMT+03:00) Baghdad','Asia/Kuwait'=>'(GMT+03:00) Kuwait','Europe/Moscow'=>'(GMT+03:00) Moscow','Africa/Nairobi'=>'(GMT+03:00) Nairobi','Asia/Riyadh'=>'(GMT+03:00) Riyadh','Europe/Volgograd'=>'(GMT+03:00) Volgograd','Asia/Tehran'=>'(GMT+03:30) Tehran','Asia/Baku'=>'(GMT+04:00) Baku','Asia/Muscat'=>'(GMT+04:00) Muscat','Asia/Tbilisi'=>'(GMT+04:00) Tbilisi','Asia/Yerevan'=>'(GMT+04:00) Yerevan','Asia/Kabul'=>'(GMT+04:30) Kabul','Asia/Karachi'=>'(GMT+05:00) Karachi','Asia/Tashkent'=>'(GMT+05:00) Tashkent','Asia/Almaty'=>'(GMT+06:00) Almaty','Asia/Dhaka'=>'(GMT+06:00) Dhaka','Asia/Novosibirsk'=>'(GMT+06:00) Novosibirsk','Asia/Rangoon'=>'(GMT+06:30) Rangoon','Asia/Bangkok'=>'(GMT+07:00) Bangkok','Asia/Jakarta'=>'(GMT+07:00) Jakarta','Asia/Krasnoyarsk'=>'(GMT+07:00) Krasnoyarsk','Asia/Chongqing'=>'(GMT+08:00) Chongqing','Asia/Irkutsk'=>'(GMT+08:00) Irkutsk','Australia/Perth'=>'(GMT+08:00) Perth','Asia/Singapore'=>'(GMT+08:00) Singapore','Singapore'=>'(GMT+08:00) Singapore','Asia/Taipei'=>'(GMT+08:00) Taipei','Asia/Urumqi'=>'(GMT+08:00) Urumqi','Asia/Seoul'=>'(GMT+09:00) Seoul','Asia/Tokyo'=>'(GMT+09:00) Tokyo','Asia/Yakutsk'=>'(GMT+09:00) Yakutsk','Australia/Adelaide'=>'(GMT+09:30) Adelaide','Australia/Darwin'=>'(GMT+09:30) Darwin','Australia/Brisbane'=>'(GMT+10:00) Brisbane','Australia/Canberra'=>'(GMT+10:00) Canberra','Pacific/Guam'=>'(GMT+10:00) Guam','Australia/Hobart'=>'(GMT+10:00) Hobart','Australia/Melbourne'=>'(GMT+10:00) Melbourne','Australia/Sydney'=>'(GMT+10:00) Sydney','Asia/Vladivostok'=>'(GMT+10:00) Vladivostok','Asia/Magadan'=>'(GMT+11:00) Magadan','Pacific/Auckland'=>'(GMT+12:00) Auckland','Pacific/Fiji'=>'(GMT+12:00) Fiji','Asia/Kamchatka'=>'(GMT+12:00) Kamchatka');
		
	/**
	 * Private method for following
	 *
	 * @access private
	 * @param int $follower_id
	 * @param int $following_id	
	 * @return 
	 */
	private function _follow($following_id, $follower_id)
	{
		$this->push($this->prefixFollower($follower_id), $following_id);
		$this->push($this->prefixFollowing($following_id), $follower_id);
		return true; //allways returns true, use transactions in calling method to check both saves
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
	 * @param string $key
	 * @return boolean
	 */
	function confirm($username, $user_id)
	{
		$this->mode = 'confirm';
		$user = $this->getByUsername($username);	//get follower
		if (!$user) 
		{
			return false;
		}		
		$requests = $this->getFriendRequests($user_id, true);
		if (($requests) AND (in_array($user['id'], $requests)))	//check if follower in in requests
		{
			$friends = $this->getFollowers($user_id);
			$this->startTransaction();
			if (($friends) AND (in_array($user['id'], $friends))) //check if follower is already following
			{	
				$this->removeFriendRequest($requests, $user['id'], $user_id);
			}
			else 
			{
				$this->_follow($user['id'], $user_id);
				$this->removeFriendRequest($requests, $user['id'], $user_id);
			}
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
			$this->delete($this->prefixUserPrivate($data['id']));
			$this->delete($this->prefixUserPublic($data['id']));			
			$this->delete($this->prefixFollower($data['email']));
			$this->delete($this->prefixFollowing($data['id']));
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
	 * @param array $user Data of user to follow
	 * @param int $user_id of user follower
	 * @return boolean
	 */
	function follow($user = array(), $user_id)
	{
		if ($user) 
		{
			$this->startTransaction();
			if ($user['locked']) 
			{
				$this->push($this->prefixFriendRequests($user['id']), $user_id);
			}
			else 
			{
				$this->_follow($user_id, $user['id']);
			}
			return $this->endTransaction();;			
		} 
		else 
		{
			return false;
		}
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
	 * Get all of a users followers  
	 *
	 * @param int $user_id
	 * @return 	
	 */
    function getFollowers($user_id)
    {
		$followers = $this->find($this->prefixFollower($user_id));
		if (!$followers) 
		{
			$followers = array(); 	//need an empty array for count to work correctly
		}
		return $followers;
    }

	/**
	 * Get all users following a user 
	 *
	 * @param int $user_id
	 * @return 	
	 */
    function getFollowing($user_id)
    {

		$following = $this->find($this->prefixFollowing($user_id));
		if (!$following) 
		{
			$following = array(); 	//need an empty array for count to work correctly
		}
		return $following;
    }

	/**
	 * Get all of a users friend requests
	 *
	 * @param int $user_id
	 * @return 	
	 */
    function getFriendRequests($user_id, $idsOnly = false)
    {
		$data = $this->find($this->prefixFriendRequests($user_id));
		if ($idsOnly || $data == false) 
		{
			return $data;
		} 
		else 
		{
			$return = array();
			foreach ($data as $request) {
				$return[] = $this->get($request);
			}
			return $return;
		}
    }
	
	/**
	 * Get the groups that a user is a member of
	 * @return 
	 * @param string $username
	 * @todo finish method
	 */
	function getGroups($username)
	{
		if ($username)
		{
			
		}
		else
		{
			return null;
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
    function isFollowing($user_id, $my_id)
    {
        $isFollowing = $this->getFollowing($my_id);
		if (!empty($isFollowing)) {
			if (in_array($user_id, $isFollowing))
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
    function isPendingFriendRequest($user_id, $my_id)
    {
        $friendRequests = $this->getFriendRequests($user_id, true);
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
	 * Remove a user from friend requests
	 *
	 * @access public
	 * @param array $requests
	 * @param int $user_id User to remove
	 * @param int $owner_id User owning list		
	 * @return 
	 */
	function removeFriendRequest($requests, $user_id, $owner_id)
	{
		$requests = $this->removeFromArray($requests, $user_id);
		return $this->save($this->prefixFriendRequests($owner_id), $requests);
	}
	

    /**
     * Send messages to followes
     *
     * @todo Might need to check to see if any data got returned to followers, that return should be meaningful
     * @param int $message_id
     * @return boolean
     */
    function sendToFollowers($message_id, $user_id)
    {
		$this->startTransaction();	
        $followers = $this->getFollowers($user_id);
		if ($followers) 
		{
			foreach ($followers as $follower)
	        {
	            $this->push($this->prefixUserPrivate($follower), $message_id);
	        }
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
		$data['id'] = $this->makeId($this->userId);
        $data['locked'] = 1;
        $data['activated'] = 1;
        $data['created'] = $now;
        $data['modified'] = $now;
        $data['time_zone'] = $this->defaultTimeZone;
		$this->startTransaction();
		$this->save($this->prefixUser($data['id']), $data);
		$this->join('UserEmail', $data['email'], $data);
		$this->join('Username', $data['username'], $data);	
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
	 * @param string $user_id of user following
	 * @return boolean
	 */
	function unfollow($username = null, $user_id)
	{
		$this->startTransaction();
		$user = $this->getByUsername($username);
		$followers = $this->getFollowers($user['id']);
		$followers = $this->removeFromArray($followers, $user_id);
		$this->save($this->prefixFollower($user['id']), $followers);
		$following = $this->getFollowing($user_id);
		$following = $this->removeFromArray($following, $user['id']);		
		$this->save($this->prefixFollowing($user_id), $following);
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