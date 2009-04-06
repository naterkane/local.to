<?php
/**
 * User Model
 */
class User extends App_Model
{
	/**
	 * Get a User's data by username
	 * @return 
	 * @param object $username[optional]
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
	 * 
	 * @return 
	 * @param object $username[optional]
	 */
    function getFollowers($username = null)
    {
        if ($username)
        {
            return $this->redis->lrange('followers:'.$username, 0, 0+1000);
        }
        else
        {
            return null;
        }
    }
	
	/**
	 * 
	 * @return 
	 * @param object $username[optional]
	 */
    function getFollowing($username = null)
    {
        if ($username)
        {
            return $this->redis->lrange('following:'.$username, 0, 0+1000);
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
        return sha1($password.$this->config->item('salt'));
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
        if (in_array($username, $isFollowing))
        {
            return true;
        }
        else
        {
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
        foreach ($followers as $follower)
        {
            $this->redis->push('private:'.$follower, $message_id, false);
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
        $user = array ();
        $now = time();
        $this->mode = 'signup';
        $user['username'] = $data['username'];
        $user['password'] = $this->hashPassword($data['password']);
        $user['activated'] = 1;
        $user['created'] = $now;
        $user['modified'] = $now;
        $message_id = $this->redis->incr("global:nextMessagetId");
        $this->save($data['username'], $user);
        //$this->redis->push($data['username'] . ':followers', $data['username'], false);
        return true;
    }
   
    /**
     * Create a new user
     *
     * @todo Add more validation rules: length of pass, length of username, allowed characters, etc
     * @access public
     * @param array $data Data to validate
     */
    /*
     function validate() {
     echo "<pre>";
     print_r($this->data);
     echo "</pre>";
     exit;
     if ($this->mode = 'signup') {
     
     } else {
     return false;
     }
     }*/
   
}

?>
