<?php
/**
 * User Model
 */
class User extends App_Model
{

	var $reservedNames = array('users', 'groups', 'admin', 'profile', 'settings', 'messages', 'tests', 'welcome');
	var $timezones = array(
		'Hawaii' => '(GMT-10:00) Hawaii',
		'Alaska' => '(GMT-09:00) Alaska',
		'Pacific Time (US &amp; Canada)' => '(GMT-08:00) Pacific Time (US &amp; Canada)',
		'Arizona' => '(GMT-07:00) Arizona',
		'Mountain Time (US &amp; Canada)' => '(GMT-07:00) Mountain Time (US &amp; Canada)',
		'Central Time (US &amp; Canada)' => '(GMT-06:00) Central Time (US &amp; Canada)',
		'Eastern Time (US &amp; Canada)' => '(GMT-05:00) Eastern Time (US &amp; Canada)',
		'Indiana (East)' => '(GMT-05:00) Indiana (East)',
		'International Date Line West' => '(GMT-11:00) International Date Line West',
		'Midway Island' => '(GMT-11:00) Midway Island',
		'Samoa' => '(GMT-11:00) Samoa',
		'Tijuana' => '(GMT-08:00) Tijuana',
		'Chihuahua' => '(GMT-07:00) Chihuahua',
		'Mazatlan' => '(GMT-07:00) Mazatlan',
		'Central America' => '(GMT-06:00) Central America',
		'Guadalajara' => '(GMT-06:00) Guadalajara',
		'Mexico City' => '(GMT-06:00) Mexico City',
		'Monterrey' => '(GMT-06:00) Monterrey',
		'Saskatchewan' => '(GMT-06:00) Saskatchewan',
		'Bogota' => '(GMT-05:00) Bogota',
		'Lima' => '(GMT-05:00) Lima',
		'Quito' => '(GMT-05:00) Quito',
		'Atlantic Time (Canada)' => '(GMT-04:00) Atlantic Time (Canada)',
		'Caracas' => '(GMT-04:00) Caracas',
		'La Paz' => '(GMT-04:00) La Paz',
		'Santiago' => '(GMT-04:00) Santiago',
		'Newfoundland' => '(GMT-03:30) Newfoundland',
		'Brasilia' => '(GMT-03:00) Brasilia',
		'Buenos Aires' => '(GMT-03:00) Buenos Aires',
		'Georgetown' => '(GMT-03:00) Georgetown',
		'Greenland' => '(GMT-03:00) Greenland',
		'Mid-Atlantic' => '(GMT-02:00) Mid-Atlantic',
		'Azores' => '(GMT-01:00) Azores',
		'Cape Verde Is.' => '(GMT-01:00) Cape Verde Is.',
		'Casablanca' => '(GMT) Casablanca',
		'Dublin' => '(GMT) Dublin',
		'Edinburgh' => '(GMT) Edinburgh',
		'Lisbon' => '(GMT) Lisbon',
		'London' => '(GMT) London',
		'Monrovia' => '(GMT) Monrovia',
		'Amsterdam' => '(GMT+01:00) Amsterdam',
		'Belgrade' => '(GMT+01:00) Belgrade',
		'Berlin' => '(GMT+01:00) Berlin',
		'Bern' => '(GMT+01:00) Bern',
		'Bratislava' => '(GMT+01:00) Bratislava',
		'Brussels' => '(GMT+01:00) Brussels',
		'Budapest' => '(GMT+01:00) Budapest',
		'Copenhagen' => '(GMT+01:00) Copenhagen',
		'Ljubljana' => '(GMT+01:00) Ljubljana',
		'Madrid' => '(GMT+01:00) Madrid',
		'Paris' => '(GMT+01:00) Paris',
		'Prague' => '(GMT+01:00) Prague',
		'Rome' => '(GMT+01:00) Rome',
		'Sarajevo' => '(GMT+01:00) Sarajevo',
		'Skopje' => '(GMT+01:00) Skopje',
		'Stockholm' => '(GMT+01:00) Stockholm',
		'Vienna' => '(GMT+01:00) Vienna',
		'Warsaw' => '(GMT+01:00) Warsaw',
		'West Central Africa' => '(GMT+01:00) West Central Africa',
		'Zagreb' => '(GMT+01:00) Zagreb',
		'Athens' => '(GMT+02:00) Athens',
		'Bucharest' => '(GMT+02:00) Bucharest',
		'Cairo' => '(GMT+02:00) Cairo',
		'Harare' => '(GMT+02:00) Harare',
		'Helsinki' => '(GMT+02:00) Helsinki',
		'Istanbul' => '(GMT+02:00) Istanbul',
		'Jerusalem' => '(GMT+02:00) Jerusalem',
		'Kyev' => '(GMT+02:00) Kyev',
		'Minsk' => '(GMT+02:00) Minsk',
		'Pretoria' => '(GMT+02:00) Pretoria',
		'Riga' => '(GMT+02:00) Riga',
		'Sofia' => '(GMT+02:00) Sofia',
		'Tallinn' => '(GMT+02:00) Tallinn',
		'Vilnius' => '(GMT+02:00) Vilnius',
		'Baghdad' => '(GMT+03:00) Baghdad',
		'Kuwait' => '(GMT+03:00) Kuwait',
		'Moscow' => '(GMT+03:00) Moscow',
		'Nairobi' => '(GMT+03:00) Nairobi',
		'Riyadh' => '(GMT+03:00) Riyadh',
		'St. Petersburg' => '(GMT+03:00) St. Petersburg',
		'Volgograd' => '(GMT+03:00) Volgograd',
		'Tehran' => '(GMT+03:30) Tehran',
		'Abu Dhabi' => '(GMT+04:00) Abu Dhabi',
		'Baku' => '(GMT+04:00) Baku',
		'Muscat' => '(GMT+04:00) Muscat',
		'Tbilisi' => '(GMT+04:00) Tbilisi',
		'Yerevan' => '(GMT+04:00) Yerevan',
		'Kabul' => '(GMT+04:30) Kabul',
		'Ekaterinburg' => '(GMT+05:00) Ekaterinburg',
		'Islamabad' => '(GMT+05:00) Islamabad',
		'Karachi' => '(GMT+05:00) Karachi',
		'Tashkent' => '(GMT+05:00) Tashkent',
		'Chennai' => '(GMT+05:30) Chennai',
		'Kolkata' => '(GMT+05:30) Kolkata',
		'Mumbai' => '(GMT+05:30) Mumbai',
		'New Delhi' => '(GMT+05:30) New Delhi',
		'Kathmandu' => '(GMT+05:45) Kathmandu',
		'Almaty' => '(GMT+06:00) Almaty',
		'Astana' => '(GMT+06:00) Astana',
		'Dhaka' => '(GMT+06:00) Dhaka',
		'Novosibirsk' => '(GMT+06:00) Novosibirsk',
		'Sri Jayawardenepura' => '(GMT+06:00) Sri Jayawardenepura',
		'Rangoon' => '(GMT+06:30) Rangoon',
		'Bangkok' => '(GMT+07:00) Bangkok',
		'Hanoi' => '(GMT+07:00) Hanoi',
		'Jakarta' => '(GMT+07:00) Jakarta',
		'Krasnoyarsk' => '(GMT+07:00) Krasnoyarsk',
		'Beijing' => '(GMT+08:00) Beijing',
		'Chongqing' => '(GMT+08:00) Chongqing',
		'Hong Kong' => '(GMT+08:00) Hong Kong',
		'Irkutsk' => '(GMT+08:00) Irkutsk',
		'Kuala Lumpur' => '(GMT+08:00) Kuala Lumpur',
		'Perth' => '(GMT+08:00) Perth',
		'Singapore' => '(GMT+08:00) Singapore',
		'Taipei' => '(GMT+08:00) Taipei',
		'Ulaan Bataar' => '(GMT+08:00) Ulaan Bataar',
		'Urumqi' => '(GMT+08:00) Urumqi',
		'Osaka' => '(GMT+09:00) Osaka',
		'Sapporo' => '(GMT+09:00) Sapporo',
		'Seoul' => '(GMT+09:00) Seoul',
		'Tokyo' => '(GMT+09:00) Tokyo',
		'Yakutsk' => '(GMT+09:00) Yakutsk',
		'Adelaide' => '(GMT+09:30) Adelaide',
		'Darwin' => '(GMT+09:30) Darwin',
		'Brisbane' => '(GMT+10:00) Brisbane',
		'Canberra' => '(GMT+10:00) Canberra',
		'Guam' => '(GMT+10:00) Guam',
		'Hobart' => '(GMT+10:00) Hobart',
		'Melbourne' => '(GMT+10:00) Melbourne',
		'Port Moresby' => '(GMT+10:00) Port Moresby',
		'Sydney' => '(GMT+10:00) Sydney',
		'Vladivostok' => '(GMT+10:00) Vladivostok',
		'Magadan' => '(GMT+11:00) Magadan',
		'New Caledonia' => '(GMT+11:00) New Caledonia',
		'Solomon Is.' => '(GMT+11:00) Solomon Is.',
		'Auckland' => '(GMT+12:00) Auckland',
		'Fiji' => '(GMT+12:00) Fiji',
		'Kamchatka' => '(GMT+12:00) Kamchatka',
		'Marshall Is.' => '(GMT+12:00) Marshall Is.',
		'Wellington' => '(GMT+12:00) Wellington',
		'Nuku\'alofa' => '(GMT+13:00) Nuku\'alofa'
	);
	
	/**
	 * Follow a user
	 *
	 * @access public
	 * @param string $username of user to follow
	 * @param string $user_id of user following
	 * @return boolean
	 */
	function follow($username = null, $user_id)
	{
		$user = $this->getByUsername($username);	
		if ($user) 
		{
			$this->push($this->prefixFollower($user['id']), $user_id);
			$this->push($this->prefixFollowing($user_id), $user['id']);
			return true;
		} 
		else 
		{
			return false;
		}
	}
	
	
	/**
	 * Get a User's data by username
	 *
	 * @param object $username[optional]
	 * @return 	
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

	function getByUsername($username = null)
	{
		$return = null;
		if ($username) 
		{
			$user_id = $this->find($this->prefixUsername($username), true);
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
	 * @param object $username
	 * @return 	
	 */
    function getFollowers($user_id)
    {
        if ($user_id)
        {
            return $this->find($this->prefixFollower($user_id));
        }
        else
        {
            return null;
        }
    }
	
	/**
	 * Get all users following a user 
	 *
	 * @param object $username
	 * @return 	
	 */
    function getFollowing($user_id)
    {
        if ($user_id)
        {
            return $this->find($this->prefixFollowing($user_id));
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
     * @param string $username The user in question
     * @param string $my_username The user doing the asking
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
	 * Is a username not reserved
	 *
	 * @access public
	 * @return boolean
	 */
	function isNotReserved()
	{
		if (in_array($this->modelData['username'], $this->reservedNames)) 
		{
			return false;
		}
		else 
		{
			return true;
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
     * @todo Might need to check to see if any data got returned to followers, that return should be meaningful
     * @param int $message_id
     * @return boolean
     */
    function sendToFollowers($message_id, $user_id)
    {
        $followers = $this->getFollowers($user_id);
		if ($followers) 
		{
			foreach ($followers as $follower)
	        {
	            $this->push($this->prefixUserPrivate($follower), $message_id);
	            $this->push($this->prefixUserPublic($follower), $message_id);	
	        }
		}
        return true;
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
     */
    function signUp($data = array ())
    {
        $user = array();
        $now = time();
        $this->mode = 'signup';
		$data['id'] = $this->makeId();
        $data['activated'] = 1;
        $data['created'] = $now;
        $data['modified'] = $now;
		if ($this->save($this->prefixUser($data['id']), $data)) 
		{
			$this->mode = null;
			$this->save($this->prefixUserEmail($data['email']), $data['id']);
			$this->save($this->prefixUsername($data['username']), $data['id']);
	        $this->push($this->prefixFollower($data['id']), $data['id']);
			$this->push($this->prefixUserPrivate($data['id']), array());
        	return true;
		} 
		else {
        	return false;
		}
    }

	/**
	 * Update profile
	 *
	 * @access public
	 * @param array $data
	 * @return boolean
	 */
	function updateProfile($user_id)
	{
		$this->mode = 'profile';
		$this->postData = $this->User->updateData($this->userData, $this->postData);
		$this->postData['id'] = $user_id;
		return $this->save($this->prefixUser($this->postData['id']), $this->postData);
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
			$this->validates_presence_of('email', array('message'=>'A valid email is required'));
			$this->validates_callback('isNotReserved', 'username', array('message'=>'This is a reserved username'));			
			$this->validates_length_of('username', array('min'=>1, 'max'=>15, 'message'=>'A username must be between 1 and 15 characters long'));
			$this->validates_uniqueness_of('username', array('message'=>'Username has already been taken', 'fieldValue'=>$this->prefixUser($this->input->post('username'))));
			$this->validates_format_of('username', array('with'=>ALPHANUM, 'message'=>'A username may only be made up of numbers, letters, and underscores'));
			$this->validates_presence_of('username', array('message'=>'A username is required'));
		}
		if ($this->mode == 'signup') 
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
			$this->validates_length_of('bio', array('min'=>0, 'max'=>160, 'message'=>'A bio must be between 1 and 160 characters long'));
		}		
	    return (count($this->validationErrors) == 0);
	}
	

}

?>