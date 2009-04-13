<?php
/**
* Extension of CI Model
*
* Allows for app-wide validation in the model and basic CRUD functionality
*  
*/
class App_Model extends Model {

	private $memcacheHost = 'localhost';
	private $memcachePort = '21201';
	private $prefixCookie = 'session';
	private $prefixFollower = 'followers';
	private $prefixFollowing = 'following';
	private $prefixGroup = 'groups';
	private $prefixGroupMessages = 'groupsmessages';	
	private $prefixGroupOwner = 'groupsowner';	
	private $prefixMessage = 'messages';
	private $prefixPrivate = 'userprivate';
	private $prefixPublic = 'timeline';
	private $prefixSeparator = ':';	
	private $prefixUser = 'users';
	private $prefixUserMessages = 'userpublic';
	public $modelData = array();
	public $validationErrors = array();
	public $mode;	

	function __construct()
	{
		$this->mem = new Memcache();
		$this->mem->connect($this->memcacheHost, $this->memcachePort) or die ("Could not connect");
	}

	/**
	 * Delete a record
	 */
	function delete($key) {
		return $this->mem->delete($key);
	}

	/**
	 * Find a record
	 *
	 * @todo This needs to behave differently depending on DB type selected. Right now does redis work which should move to redis library extension
	 * @access public
	 */
	function find($key) {
		$data = $this->mem->get($key);
		if ($this->isSerialized($data)) {
			$data = unserialize($data);
		}
		return $data;
	}

	/**
	 * Return the number of validation errors
	 *
	 * @return int Number of errors
	 * @access public
	 */
	function invalidFields() {
		return count($this->validationErrors);
	}

	/**
	 * Is a string serialized?
	 *
	 * @param string
	 * @return boolean 	
	 */
	function isSerialized($str) {
	    return ($str == serialize(false) || @unserialize($str) !== false);
	}

	/**
	 * Load models from controller into model
	 *
	 * @access public
	 * @param array $models
	 * @return 
	 */
	function loadModels($models)
	{
		$ci = CI_Base::get_instance();
		foreach ($models as $model) 
		{
			$this->$model = $ci->$model;
		}
	}

	/**
	 * Create a prefix for cookie
	 * 
	 * @access public
	 * @param string $username
	 * @return string
	 */
	function prefixCookie($key)
	{ 
		return $this->prefixCookie . $this->prefixSeparator . $key; 
	}

	/**
	 * Create a prefix for followers
	 * 
	 * @access public
	 * @param string $username
	 * @return string
	 */
	function prefixFollower($username)
	{ 
		return $this->prefixFollower . $this->prefixSeparator . $username; 
	}
	
	/**
	 * Create a prefix for followers
	 * 
	 * @access public
	 * @param string $username
	 * @return string
	 */	
	function prefixFollowing($username)
	{ 
		return $this->prefixFollowing . $this->prefixSeparator . $username; 
	}
	
	/**
	 * Create a prefix for a group
	 * 
	 * @access public
	 * @param string $groupname
	 * @return string
	 */	
	function prefixGroup($groupname)
	{ 
		return $this->prefixGroup . $this->prefixSeparator . $groupname; 
	}

	/**
	 * Create a prefix for group messages
	 * 
	 * @access public
	 * @param string $groupname
	 * @return string
	 */	
	function prefixGroupMessages($groupname)
	{ 
		return $this->prefixGroupMessages . $this->prefixSeparator . $groupname; 
	}

	/**
	 * Create a prefix for a group owner
	 * 
	 * @access public
	 * @param string $groupname
	 * @return string
	 */	
	function prefixGroupOwner($groupname)
	{ 
		return $this->prefixGroupOwner . $this->prefixSeparator . $groupname; 
	}
	
	/**
	 * Create a prefix for a message
	 * 
	 * @access public
	 * @param string $username
	 * @param string $time	
	 * @return string
	 */	
	function prefixMessage($username, $time)
	{ 
		return $this->prefixMessage . $this->prefixSeparator . $username . $this->prefixSeparator . $time; 
	}
	
	/**
	 * Create a prefix for private messages
	 * 
	 * @access public
	 * @param string $username
	 * @return string
	 */	
	function prefixPrivate($username)
	{ 
		return $this->prefixPrivate . $this->prefixSeparator . $username; 
	}
	
	/**
	 * Create a prefix for public messages
	 * 
	 * @access public
	 * @return string
	 */	
	function prefixPublic()
	{ 
		return $this->prefixPublic . $this->prefixSeparator; 
	}

	/**
	 * Create a prefix for a user
	 * 
	 * @access public
	 * @param string $username
	 * @return string
	 */	
	function prefixUser($username)
	{ 
		return $this->prefixUser . $this->prefixSeparator . $username; 
	}

	/**
	 * Create a prefix for user messages
	 * 
	 * @access public
	 * @param string $username
	 * @return string
	 */
	function prefixUserMessages($username)
	{ 
		return $this->prefixUserMessages . $this->prefixSeparator . $username; 
	}

	/**
	 * Add a value to the end of a serialized value
	 * 
	 * @access public
	 * @param string $key Key of record to push
	 * @param string $value Value to push onto data
	 * @return boolean 		 
	 */
	function push($key, $value)
	{
		$data = $this->find($key, $value);
		if (!$data) {
			$data = array();
		}
		if (is_array($data)) {
			array_unshift($data, $value);
			$data = serialize($data);
			return $this->save($key, $data);
		} else {
			return false;
		}	
	}
	
	/**
	 * Trim down an value to a certain length 
	 *
	 * @access public
	 * @param string $key 
	 * @param int $offset 	
	 * @param int $length	
	 * @return boolean
	 */
	function trim($key, $offset, $length)
	{
		$data = $this->find($key);
		if (is_array($data)) {
			array_slice($data, $offset, $length);
			$data = serialize($data);
			return $this->save($key, $data);
		} else {
			return false;
		}
	}
	
	/**
	 * Save a record
	 *
	 * @todo See find() above
	 * @todo Should reflect the actual outcome of the save, right now always returns true if data validates	
	 * @return boolean
	 * @access public
	 */
	function save($key, $data) {
		$this->modelData = $data;
		if ($this->validate()) {
			return $this->mem->set($key, $data);
		} else {
			return false;
		}
	}
	
	/**
	 * Validate data
	 *
	 * Empty method to be inherited by models. 
	 *
	 * @todo Hook up to CI's validation class
	 * @param array $data
	 * @return boolean
	 * @access public
	 */	
	function validate() {
		return true;
	}
	
}

?>