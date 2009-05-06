<?php
/**
* Extension of CI Model
*
* Allows for app-wide validation in the model and basic CRUD functionality
*  
*/
class App_Model extends Model {

	protected $key;
	protected $memcacheHost = 'localhost';
	protected $memcachePort = '21201';
	protected $prefixCookie = 'session';
	protected $prefixFollower = 'followers';
	protected $prefixFollowing = 'following';	
	protected $prefixFriendRequests = 'friendrequests';	
	protected $prefixGroup = 'group';
	protected $prefixGroupName = 'groupname';	
	protected $prefixGroupMessages = 'groupmessages';
	protected $prefixGroupMembers = 'groupmembers';	
	protected $prefixMessage = 'message';
	protected $prefixPublic = 'timeline';
	protected $prefixPublicThreaded = 'timelinethreaded';	
	protected $prefixReplies = 'replies';	
	protected $prefixSeparator = ':';	
	protected $prefixUser = 'user';
	protected $prefixUsername = 'username';
	protected $prefixUserEmail = 'useremail';	
	protected $prefixUserPublic = 'public';
	protected $prefixUserPrivate = 'private';
	protected $groupId = 'groupId';	
	protected $messageId = 'messageId';		
	protected $queryCount = 0;
	protected $queryErrors = 0;
	protected $queryLog = array();
	protected $reservedNames = array('users', 'groups', 'admin', 'profile', 'settings', 'messages', 'tests', 'welcome');
	protected $rollbackLog = array();	
	protected $transactional = false;		
	protected $userId = 'userId';
	public $action;
	public $id;	
	public $locked = false;	
	public $modelData = array();
	public $mode;
	public $validate = true;
	public $validationErrors = array();	

	function __construct()
	{
		$ci = CI_Base::get_instance();
		$this->loadLibrary(array('Tt'));
		if( $_SERVER['HTTP_HOST'] == $ci->config->item('tt_host'))
		{
			$host = $ci->config->item('tt_host_local');
		}
		else
		{
			$host = $ci->config->item('tt_host_remote');
		}
		$port = $ci->config->item('tt_host_port');
		$this->tt->connect($host, $port);
		$this->mem = new Memcache;
		$this->mem->connect($host, $port) or die ("Could not connect to memcached through tc");		
		unset($ci);
	}

	/**
	 * Log a query for transactions
	 *
	 * @access protected
	 * @param string $key
	 * @param boolean $result	
	 * @return 
	 */	
	protected function logQuery($key)
	{
		if ($this->transactional) 
		{
			$this->queryLog[$this->queryCount] = array();
			$this->queryLog[$this->queryCount]['key'] = $key;
			if (!is_null($key)) 
			{
				$data = $this->find($key);
			}
			else 
			{
				$data = null;
			}
			$this->queryLog[$this->queryCount]['rollback'] = $data;
		}
	}

	/**
	 * Log a query for transactions
	 *
	 * @access protected
	 * @param string $key
	 * @param boolean $result	
	 * @return 
	 */	
	protected function logQueryResult($result, $data)
	{
		if ($this->transactional)
		{
			$this->queryLog[$this->queryCount]['result'] = $result;
			$this->queryLog[$this->queryCount]['data_written'] = $data;			
			if (!$result) 
			{
				$this->queryErrors++;
			}
			$this->queryCount++;
		}
	}

	/**
	 * Delete a record
	 */
	function delete($key) 
	{
		$this->logQuery($key);
		$data = $this->mem->get(array($key));
		if (!empty($data)) 
		{
			$result = $this->mem->delete($key);
		}
		else 
		{
			$result = false;
		}
		$this->logQueryResult($result, null);
		return $result;
	}
	
	/**
	 * Do transaction
	 *
	 */
	function endTransaction()
	{
		if ((!$this->transactional) || ($this->queryCount === 0))
		{
			return true;
		}
		$this->transactional = false;	
		if ($this->queryErrors > 0) 
		{
			$queries = array_reverse($this->queryLog);
			$i = 0;
			foreach ($queries as $query) {								
				if ($query['result']) 
				{
					if ($query['rollback']) 
					{
						$type = 'save';
						$result = $this->save($query['key'], $query['rollback'], false);
					} 
					else 
					{
						$type = 'delete';
						$result = $this->delete($query['key']);
					}
					$this->rollbackLog[$i]['key'] = $query['key'];
					$this->rollbackLog[$i]['type'] = $type;
					$this->rollbackLog[$i]['data'] = $query['rollback'];
					$this->rollbackLog[$i]['result'] = $result;
					$i++;
				}
			}
			return false;
		}
		return true;
	}

	/**
	 * Find a record
	 *
	 * @access public
	 */
	function find($key) 
	{
		$data = $this->mem->get($key);
		if ($this->isSerialized($data)) 
		{
			$data = unserialize($data);
		}
		return $data;
	}

	/**
	 * Get Value from model data
	 *
	 * @access public
	 * @param array $data
	 * @param string $fieldname
	 * @return string|null
	 */
	function getValue($data, $fieldname)
	{
		
		if (isset($data[$fieldname])) 
		{
			return $data[$fieldname];
		} 
		else 
		{
			return null;
		}	
	}

	/**
	 * Hash a value
	 *
	 * @access public
	 * @param string $value
	 * @return string Hashed value
	 */
	function hash($value)
	{
		return sha1($value . $this->config->item('salt'));
	}

	/**
	 * Is a username not reserved
	 *
	 * @access public
	 * @return boolean
	 */
	function isNotReserved($fieldName)
	{
		if (isset($this->modelData[$fieldName])) 
		{
			if (in_array($this->modelData[$fieldName], $this->reservedNames)) 
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
	 * Is there an error>
	 *
	 * @access public
	 * @return boolean
	 */
	function isValid()
	{
		if (count($this->validationErrors) > 0) 
		{
			return true;
		} 
		else 
		{
			return false;
		}
	}
	
	/**
	 * Make a join
	 */
	function join($prefixName, $prefix, $data)
	{
		$this->mode = 'join';
		$prefixName = 'prefix' . $prefixName;
		$newPrefix = $this->{$prefixName}($prefix);
		$this->save($newPrefix, $data);
	}
	
	/**
	 * Is a string serialized?
	 *
	 * @param string
	 * @return boolean 	
	 */
	function isSerialized($str) 
	{
		if ($str === 0) 
		{
			return false;
		} 
		else 
		{
	    	return ($str == serialize(false) || @unserialize($str) !== false);
		}
	}

	/**
	 * Check if a submitted time zone is in the PHP global list
	 *
	 * @access public
	 * @return boolean
	 */
	function isTimeZone()
	{
		if (empty($this->modelData['time_zone'])) 
		{
			return false;
		}
		$reversed_zones = array_flip($this->timeZones);
		return in_array($this->modelData['time_zone'], $reversed_zones);
	}
	

	/**
	 * Load models from controller into model
	 *
	 * @access public
	 * @param array $models
	 * @return 
	 */
	function loadLibrary($files = array())
	{
		foreach ($files as $file) 
		{
			$path = APPPATH . 'libraries/' . $file . '.php';			
			if (file_exists($path)) 
			{
				include_once($path);
				$class_name = strtolower($file);
				$this->{$class_name} = new $file;
			}
		}
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
	 * Make an id for a model
	 *
	 * @access public
	 * @param string value
	 * @return id
	 */
	function makeId($key)
	{
		$last_id = $this->mem->increment($key);
		if (!$last_id) 
		{
			$last_id = 1;
			$this->save($key, $last_id);			
		}
		return $last_id;
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
	function prefixFollower($id)
	{ 
		return $this->prefixFollower . $this->prefixSeparator . $id; 
	}
	
	/**
	 * Create a prefix for followers
	 * 
	 * @access public
	 * @param string $id
	 * @return string
	 */	
	function prefixFollowing($id)
	{ 
		return $this->prefixFollowing . $this->prefixSeparator . $id; 
	}

	/**
	 * Create a prefix for a follow request
	 * 
	 * @access public
	 * @param string $id
	 * @return string
	 */	
	function prefixFriendRequests($id)
	{ 
		return $this->prefixFriendRequests . $this->prefixSeparator . $id; 
	}
	
	/**
	 * Create a prefix for a group
	 * 
	 * @access public
	 * @param string $id
	 * @return string
	 */	
	function prefixGroup($id)
	{ 
		return $this->prefixGroup . $this->prefixSeparator . $id; 
	}
	
	/**
	 * Create a prefix for a group name
	 * 
	 * @access public
	 * @param string $groupname
	 * @return string
	 */	
	function prefixGroupName($groupname)
	{ 
		return $this->prefixGroupName . $this->prefixSeparator . $groupname; 
	}
	
	/**
	 * Create a prefix for a group members
	 * 
	 * @access public
	 * @param int $group_id
	 * @return string
	 */	
	function prefixGroupMembers($group_id)
	{ 
		return $this->prefixGroupMembers . $this->prefixSeparator . $group_id; 
	}
	
	/**
	 * Create a prefix for a group messages
	 * 
	 * @access public
	 * @param int $group_id
	 * @return string
	 */	
	function prefixGroupMessages($group_id)
	{ 
		return $this->prefixGroupMessages . $this->prefixSeparator . $group_id; 
	}
	
	/**
	 * Create a prefix for a message
	 * 
	 * @access public
	 * @param int $id	
	 * @return string
	 */	
	function prefixMessage($id)
	{ 
		return $this->prefixMessage . $this->prefixSeparator  . $id; 
	}
	
	/**
	 * Create a prefix for public messages
	 * 
	 * @access public
	 * @return string
	 */	
	function prefixPublic()
	{ 
		return $this->prefixPublic; 
	}
	
	/**
	 * Create a prefix for public messages threaded
	 * 
	 * @access public
	 * @return string
	 */	
	function prefixPublicThreaded()
	{ 
		return $this->prefixPublicThreaded; 
	}
	
	
	/**
	 * Create a prefix for replies
	 * 
	 * @access public
	 * @param int $id
	 * @return string
	 */	
	function prefixReplies($id)
	{ 
		return $this->prefixReplies . $this->prefixSeparator  . $id; 
	}	

	/**
	 * Create a prefix for user data
	 * 
	 * @access public
	 * @param string $username
	 * @return string
	 */	
	function prefixUser($id)
	{ 
		return $this->prefixUser . $this->prefixSeparator . $id; 
	}

	/**
	 * Create a prefix for a username
	 * 
	 * @access public
	 * @param string $username
	 * @return string
	 */	
	function prefixUserEmail($email)
	{ 
		return $this->prefixUserEmail . $this->prefixSeparator . $email; 
	}
	
	/**
	 * Create a prefix for a username
	 * 
	 * @access public
	 * @param string $username
	 * @return string
	 */	
	function prefixUsername($username)
	{ 
		return $this->prefixUsername . $this->prefixSeparator . $username; 
	}
	

	/**
	 * Create a prefix for user messages
	 * 
	 * @access public
	 * @param string $username
	 * @return string
	 */
	function prefixUserPrivate($username)
	{ 
		return $this->prefixUserPrivate . $this->prefixSeparator . $username; 
	}

	/**
	 * Create a prefix for user messages
	 * 
	 * @access public
	 * @param string $username
	 * @return string
	 */
	function prefixUserPublic($username)
	{ 
		return $this->prefixUserPublic . $this->prefixSeparator . $username; 
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
		$data = $this->find($key);
		if (!$data) {
			$data = array();
		}
		if (is_array($data)) {
			if (in_array($value, $data)) 
			{
				return false;
			} 
			else 
			{
				array_unshift($data, $value);
				$data = serialize($data);
				return $this->save($key, $data);
			}
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
	 * Remove an item from an array and reorder (used to remove followers, members, etc)
	 *
	 * @param array $data
	 * @param string $value 
	 * @return array $data
	 * @access public
	 */
	function removeFromArray($data, $value)
	{
		$data = array_flip($data);
		unset($data[$value]);
		$data = array_flip($data);
		return array_merge(array(), $data);
	}
	
	function rollbackLog()
	{
		return $this->rollbackLog;
	}
	
	/**
	 * Save a record
	 *
	 * @todo See find() above
	 * @todo Should reflect the actual outcome of the save, right now always returns true if data validates	
	 * @return boolean
	 * @access public
	 */
	function save($key, $data, $validate = true) 
	{		
		$valid = true;
		$newData = null;
		$this->key = $key;
		$this->modelData = $data;
		$this->logQuery($key, 'save');		
		if ($validate) 
		{
			$valid = $this->validate();
		}
		if ($valid) {
			if (is_array($this->modelData)) 
			{
				$newData = serialize($this->modelData);
			}
			else 
			{
				$newData = $this->modelData;
			}
			if ($key) 
			{
				$valid = $this->mem->set($key, $newData);
			}
		}
		$this->logQueryResult($valid, $newData);					
		return $valid;
	}

	/**
	 * Set the save action for validation
	 */
	function setAction()
	{
        if (empty($this->id)) {
                $this->action = 'create';
        } else {
                $this->action = 'update';
        }
        return true;
	}

	/**
	 * Transactional
	 */
	function startTransaction()
	{
		$this->transactional = true;
		$this->queryLog = array();
		$this->queryCount = 0;
		$this->queryErrors = 0;		
		$this->rollbackLog = array();			
	}

	/**
	 * Show the query log
	 */
	function queryLog()
	{
		return $this->queryLog;
	}
	
	/**
	 * Compare values of two arrays before doing an update
	 *
	 * Because key value databases do not do tradition inserts (they overwrite all data) we need to make sure we save all fields back to the database
	 *
	 * @access public
	 * @param array $oldData[optional]
	 * @param array $newData[optional]	
	 * @return $oldData;
	 */
	function updateData($oldData = array(), $newData = array())
	{
		foreach ($newData as $key => $value) {
			$oldData[$key] = $value;
		}
		return $oldData;
	}
	
	
	/**
	 * Validate data
	 *
	 * Empty method to be inherited by models. 
	 *
	 * @param array $data
	 * @return boolean
	 * @access public
	 */	
	function validate() 
	{
		return true;
	}

	/**
	 * Validate by calling a user-created callback
	 *
	 * @param string $callback
	 * @param string $fieldName Name of field to be validated
	 * @param array $options[optional]
	 * @return boolean
	 * @access public
	 */	
	function validates_callback ($callback, $fieldName, $options=array()) 
	{
        if ( !isset($options['message']) ) 
		{
                $options['message'] = 'The field is incorrectly entered.';
        }	
		if (!call_user_func(array($this, $callback), $fieldName)) 
		{
			$this->validationErrors[$fieldName] = $options['message'];
		}
	}

	/* Pending
    function validates_condition_of($conditions, $fieldName,$options=array()) 
	{
			$fieldValue = $this->getValue($fieldName);
            if ( @$options['allow_null'] && ($fieldValue == null) ) 
			{
                    return true;
            }
            if ( !isset($options['message']) ) 
			{
                    $options['message'] = 'The '. strtolower(Inflector::humanize($fieldName)) . ' you entered is already in use by another record. The '. strtolower(Inflector::humanize($fieldName)) . ' must be unique.';
            }
            if ( !isset($options['on']) ) 
			{
                    $options['on'] = 'save';
            }
			$hasAny_conditions = $conditions;
			if ($this->id) 
			{
				$hasAny_conditions .= ' AND ' . $this->name . '.id!=' . $this->id;				
			}
			if ( $this->hasAny($hasAny_conditions)) 
			{
				$this->validationErrors[$fieldName] = $options['message'];
			}
    }*/

	/* Pending
	function validates_existence_of($fieldName, $options=array())  
	{
		$fieldValue = $this->getValue($fieldName);
        if ( @$options['allow_null'] && ($fieldValue == null) ) 
		{
                return true;
        }
        if ( !isset($options['message']) ) 
		{
                $options['message'] = 'The '. strtolower(Inflector::humanize($fieldName)) . ' you entered is already in use by another record. The '. strtolower(Inflector::humanize($fieldName)) . ' must be unique.';
        }
        if ( !isset($options['on']) ) 
		{
                $options['on'] = 'save';
        }
		$hasAny_conditions = $this->name . '.' . $fieldName . '=\'' . $fieldValue . '\'';
		if ($this->action != 'create') 
		{
			$hasAny_conditions .= ' AND ' . $this->name . '.id !=\'' . $this->id . '\'';
		}
		if ( !$this->hasAny($hasAny_conditions)) 
		{
		       $this->validationErrors[$fieldName] = $options['message'];
		}
	}*/

	/* Pending
    function validates_exclusion_of($fieldName,$options=array()) 
	{
			$fieldValue = $this->getValue($fieldName);
            if ( @$options['allow_null'] && ($fieldValue == null) ) 
			{
                    return true;
            }
            if ( !isset($options['in']) ) 
			{
                    $options['in'] = array();
            }
            if ( !isset($options['message']) ) {
                    $options['message'] = 'This ' . Inflector::humanize($fieldName) . ' is reserved by the system and can not be used.';
            }
            if ( !isset($options['on']) ) {
                    $options['on'] = 'save';
            }
            if ( (($options['on'] == 'save') || ($options['on'] == $this->action)) ) 
			{
                    if ( in_array($fieldValue,$options['in']) ) {
                            $this->validationErrors[$fieldName] = $options['message'];
                    }
            }
    }*/

	/**
	 * Validate by checking for format of value
	 * 
	 * @return 
	 * @param object $fieldName
	 * @param object $options[optional]
	 */
    function validates_format_of($fieldName, $options=array()) 
	{
		$fieldValue = $this->getValue($this->modelData, $fieldName);		
		if ((isset($options['allow_null'])) AND (!$fieldValue)) 
		{	
			return true;
		}		
		if ( !isset($options['message']) ) 
		{	
			$options['message'] = 'Field has an invalid format.';
		}
		if ( !isset($options['on']) ) 
		{
			$options['on'] = 'save';
		}
		if ( !isset($options['with']) ) 
		{
			$options['with'] = '//';
		}
		if ( (($options['on'] == 'save') || ($options['on'] == $this->action)) ) 
		{
			if ( !preg_match($options['with'],$fieldValue) ) 
			{
			       $this->validationErrors[$fieldName] = $options['message'];
			}
		}
    }

	/**
	 * Validate a join
	 * 
	 */
    function validates_join() 
	{
		if (empty($this->modelData['id'])) 
		{
			$this->validationErrors[$this->key] = 'An ID is needed for a join.';
		}
		else 
		{
			$this->modelData = $this->modelData['id'];
		}
    }

	/* Pending
    function validates_inclusion_of($fieldName,$options=array()) 
	{
            $fieldValue = $this->getValue($this->data);
            if ( @$options['allow_null'] && ($fieldValue == null) ) 
			{
                    return true;
            }
            if ( !isset($options['in']) ) 
			{
                    $options['in'] = array();
            }
            if ( !isset($options['message']) ) 
			{
                    $options['message'] = Inflector::humanize($fieldName) . ' should be one of the following: ' . join(', ' ,$options['in']) . '.';
            }
            if ( !isset($options['on']) ) 
			{
                    $options['on'] = 'save';
            }
            if ( (($options['on'] == 'save') || ($options['on'] == $this->action)) ) 
			{
                    if ( !in_array($fieldValue,$options['in']) ) 
					{
                            $this->validationErrors[$fieldName] = $options['message'];
                    }
            }
    }*/

	/**
	 * Validate by checking length of value
	 * 
	 * @return 
	 * @param object $fieldName
	 * @param object $options[optional]
	 */
    function validates_length_of($fieldName, $options=array()) 
	{
		$fieldValue = $this->getValue($this->modelData, $fieldName);
		if (!isset($options['message'])) 
		{
			$options['message'] = 'Field has the wrong length.';
		}
		if (!isset($options['on'])) 
		{
			$options['on'] = 'save';
		}
		if ((($options['on'] == 'save') || ($options['on'] == $this->action))) 
		{
			if (( strlen($fieldValue) > $options['max'] ) || ( strlen($fieldValue) < $options['min'] ))
			{
				$this->validationErrors[$fieldName] = $options['message'];
			}
		}
    }

	/* Pending
    function validates_numericality_of($fieldName,$options=array()) 
	{
            $fieldValue = $this->getValue($this->data);
            if ( @$options['allow_null'] && ($fieldValue == null) ) 
			{
                    return true;
            }
            if ( !isset($options['only_integer']) ) 
			{
                    $options['only_integer'] = false;
            }
            if ( !isset($options['message']) ) 
			{
                    if ( $options['only_integer'] ) 
					{
                            $options['message'] = Inflector::humanize($fieldName) . ' should be an integer.';
                    } else {
                            $options['message'] = Inflector::humanize($fieldName) . ' should be a number.';
                    }
            }
            if ( !isset($options['on']) ) 
			{
                    $options['on'] = 'save';
            }
            if ( (($options['on'] == 'save') || ($options['on'] == $this->action)) ) 
			{
                    if (!is_numeric($fieldValue) || ( $options['only_integer'] && !is_int($fieldValue) )) 
					{
                            $this->validationErrors[$fieldName] = $options['message'];
                    }
            }
    }*/

	/**
	 * Validate by checking for field value presence
	 *
	 * @param string $fieldName Name of field to be validated
	 * @param array $options[optional]
	 * @return boolean
	 * @access public
	 */
    function validates_presence_of($fieldName, $options=array()) 
	{
			$fieldValue = $this->getValue($this->modelData, $fieldName);
            if ( !isset($options['message']) ) 
			{
				$options['message'] = 'This field is required.';
            }
            if ( !isset($options['on']) ) 
			{
                    $options['on'] = 'save';
            }
            if ( (($options['on'] == 'save') || ($options['on'] == $this->action)) ) 
			{	
				if ($fieldValue == '') 
				{
					$this->validationErrors[$fieldName] = $options['message'];
				}			
            }
    }

	/* Pending
    function validates_quantity_of($fieldName,$options=array()) 
	{
	        if ( !isset($options['less_than']) ) 
			{
	                $options['less_than'] = 100;
	        }
	        if ( !isset($options['more_than']) ) 
			{
	                $options['more_than'] = 0;
	        }	
            if ( !isset($options['message']) ) 
			{
                    $options['message'] = Inflector::humanize($fieldName) . ' must be between ' . $options['more_than'] . ' and ' . $options['less_than'] . '.';
            }
            if ( !isset($options['on']) ) 
			{
                    $options['on'] = 'save';
            }
            if ( (($options['on'] == 'save') || ($options['on'] == $this->action)) ) 
			{
                    if (($this->data[$this->name][$fieldName] > $options['less_than']) || ($this->data[$this->name][$fieldName] < $options['more_than'])) {
                            $this->validationErrors[$fieldName] = $options['message'];
                    }
            }
    }*/

	/**
	 * Validate the uniqueness of a value
	 *
	 * @todo add code for updates, currently only works for creates.
	 * @todo add code for mysql
	 * @access public
	 * @param string $fieldname
	 * @param array $options
	 * @return boolean
	 */
    function validates_uniqueness_of($fieldName=null, $options = array()) 
	{
			if (!isset($options['fieldValue'])) 
			{
				$fieldValue = $this->input->post($fieldName);
			} 
			else 
			{
				$fieldValue = $options['fieldValue'];
			}
            if ( !isset($options['message']) ) 
			{
				$options['message'] = 'The value you entered is already in use by another record.';
            }
			if (empty($this->postData[$fieldName])) 
			{
				$this->postData[$fieldName] = null;
			}
			if (empty($this->userData[$fieldName])) 
			{
				$this->userData[$fieldName] = null;
			}
			$record = $this->find($fieldValue);
			if ($record) 
			{
				if ($this->postData[$fieldName] != $this->userData[$fieldName]) 
				{
					$this->validationErrors[$fieldName] = $options['message'];
				}
			}
    }
	
}

?>