<?php
/**
 * User Model
 */
class User extends App_Model
{
	/**
	 * Get a User's data by username
	 *
	 * @param object $username[optional]
	 * @return 	
	 */
    function get($username = null)
    {
        if ($username)
        {
            return $this->find($username);
        }
        else
        {
            return null;
        }
    }
	
	/**
	 * Get all of a users followers  
	 *
	 * @param object $username
	 * @return 	
	 */
    function getFollowers($username)
    {
        if ($username)
        {
            return $this->find('followers:' . $username);
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
    function getFollowing($username)
    {
        if ($username)
        {
            return $this->find('following:' . $username);
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
        return sha1($password . $this->config->item('salt'));
    }
   
    /**
     * Check if a user is following another user
     *
     * @param string $username The user in question
     * @param string $my_username The user doing the asking
     * @return boolean
     */
    function isFollowing($username, $my_username)
    {
        $isFollowing = $this->getFollowing($my_username);
		if (!empty($isFollowing)) {
			if (in_array($username, $isFollowing))
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
     * Send messages to followes
     *
     * @todo Might need to check to see if any data got returned to followers, that return should be meaningful
     * @param int $message_id
     * @return boolean
     */
    function sendToFollowers($message_id, $username)
    {
        $followers = $this->getFollowers($username);
		if ($followers) {
			foreach ($followers as $follower)
	        {
	            $this->push('private:' . $follower, $message_id);
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
            $user = $this->find($data['username']);
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
        $user['username'] = $data['username'];
        $user['password'] = $this->hashPassword($data['password']);
        $user['activated'] = 1;
        $user['created'] = $now;
        $user['modified'] = $now;
        $this->save($data['username'], $user);
        $this->push('followers:' . $data['username'], $data['username']);
		$this->push('private:' . $follower, $message_id);
        return true;
    }
   
}

?>
