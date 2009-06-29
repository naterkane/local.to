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
 * App_Model
 * 
 * Extension of CI Model
 *
 * Allows for app-wide validation in the model and basic CRUD functionality
 * 
 * @package 	Nomcat
 * @subpackage	Libraries
 * @category	Model
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class App_Model extends Model {

	/**
	 * @access protected
	 * @static
	 * @var integer
	 */
	protected static $queryCount = 0;
	/**
	 * @access protected
	 * @static
	 * @var integer
	 */
	protected static $queryErrors = 0;
	/**
	 * @access protected
	 * @static
	 * @var array
	 */
	protected static $queryLog = array();
	/**
	 * @access protected
	 * @static
	 * @var array
	 */
	protected static $rollbackLog = array();
	/**
	 * @access protected
	 * @static
	 * @var boolean
	 */
	protected static $transactional = false;
	/**
	 * @access protected
	 * @var array
	 */	
	protected $fields = array();
	/**
	 * @access protected
	 * @var mixed
	 */	
	protected $key;
	/**
	 * @access protected
	 * @var mixed
	 */	
	protected $idGenerator;
	/**
	 * @access protected
	 * @var string
	 * @todo move the setting of $memcacheHost to config
	 */	
	protected $memcacheHost = '67.23.9.219';
	/**
	 * @access protected
	 * @var integer
	 * @todo move the setting of $memcachePort to config
	 */	
	protected $memcachePort = '21201';
	/**
	 * @access protected
	 * @var string
	 */	
	protected $name;
	/**
	 * @access protected
	 * @var string
	 */	
	protected $prefixSeparator = ':';
	/**
	 * reservedNames should be compared to the defined routes to make sure that it includes any names that may be used by the system in a URI.
	 * @access protected
	 * @var array
	 * @see /system/application/config/routes.php
	 */
	protected $reservedNames = array(
									'delete',
									'add',
									'signin',
									'signout',
									'confirm',
									'deny',
									'reset',
									'reset_password',
									'change_password',
									'friend_requests',
									'public_timeline',
									'home',
									'default_controller',
									'scaffolding_trigger',
									'users', 
									'groups', 
									'admin', 
									'profile', 
									'settings', 
									'messages', 
									'tests', 
									'testme',
									'welcome', 
									'about',
									'user',
									'group');
	/**
	 * @access protected
	 * @var mixed
	 */
	protected $table;	
	/**
	 * @var string
	 */
	public $action;
	/**
	 * @var integer|string
	 */
	public $id;	
	/**
	 * @var boolean
	 */
	public $locked = false;	
	/**
	 * @var array
	 */
	public $modelData = array();
	/**
	 * @var string
	 */
	public $mode;
	/**
	 * @var boolean
	 */
	public $validate = true;
	/**
	 * @var array
	 */
	public $validationErrors = array();
	/**
	 * @var integer|string
	 */
	public $insertId;		

	/**
	 * 
	 * @return 
	 */
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
		$this->mem->addServer($host, $port,true,1000,10000) or die ("Could not connect to memcached through tc");		
		unset($ci);
	}

	/**
	* Random Alpha-Numeric String
	*
	* @param int length
	* @param array $chars length	
	* @return string 
	* @access private
	*/
	private function _random($length, $chars) {
		$randstr = null;
		srand();
		for ($rand = 0; $rand < $length; $rand++) {
			$random = rand(0, count($chars) -1);
			$randstr .= $chars[$random];
		}
		return $randstr;
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
		if (self::$transactional) 
		{
			self::$queryLog[self::$queryCount] = array();
			self::$queryLog[self::$queryCount]['key'] = $key;
			if (!is_null($key)) 
			{
				$data = $this->find($key);
			}
			else 
			{
				$data = null;
			}
			self::$queryLog[self::$queryCount]['rollback'] = $data;
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
		if (self::$transactional)
		{
			self::$queryLog[self::$queryCount]['result'] = $result;
			self::$queryLog[self::$queryCount]['data_written'] = $data;			
			if (!$result) 
			{
				self::$queryErrors++;
			}
			self::$queryCount++;
		}
	}

	/**
	 * Add to a nested array
	 *
	 * @access public
	 * @param string $arrayName
	 * @param string $prefix
	 * @param array $data Passed by reference	
	 * @param int $id
	 * @return array $data
	 */
	public function addTo($arrayName, &$data, $id, $push = false)
	{
		if ($push) 
		{
			$data[$arrayName][] = $id;
		}
		else
		{
			array_unshift($data[$arrayName], $id);
		}
		return $this->save($data);
	}
	
	/**
	 * Base64 encode a string that is url safe
	 * 
	 * Any string encoded with this method must be decoded with base64_url_decode()
	 * 
	 * @see base64_url_decode()
	 * @param object $input
	 * @return string base64 encoded string
	 */
	public function base64_url_encode($input) {
    	return strtr(base64_encode($input), '+/=', '-_,');
    }

	/**
	 * Base64 decode a string that has been encoded with base64_url_encode
	 * 
	 * @see base64_url_encode()
	 * @param object $input
	 * @return string
	 */
	public function base64_url_decode($input) {
	    return base64_decode(strtr($input, '-_,', '+/='));
    }

	/**
	 * Set up a new record
	 * Takes the data passed to it and adds the values of those fields passed to it. 
	 * Will not add any fields not in the models $fields variable
	 * 
	 * @see getFields()
	 * @access public
	 * @param array $post Data passed by form
	 * @return array
	 */
	public function create($post = array())
	{
		$data = $this->getFields();
		if (!is_array($post)) 
		{
			return $data;
		}
		if (empty($post)) 
		{
			return $data;
		}
		foreach ($data as $key => $value) {
			if (isset($post[$key])) 
			{
				$data[$key] = trim($post[$key]);
			}
		}
		return $data;
	}
	

	/**
     * Delete a record
     * 
     * @see makeFindPrefix()
     * @see logQuery()
     * @see Memcache
     * @see Memcache::get()
     * @see Memcache::delete()
     * @param array $value[optional] Message data
     * @param array $options 
	 * 					[override] string Override the entire key, e.g. 'publictimeline'	
	 * 					[prefixName] string Override the first part of the prefix, the class name
	 * 					[prefixValue] string Override the the value of the key, e.g. 'email' instead of the default, id			
	 * @return boolean
     */
	public function delete($value = null, $options = array())
	{
		$key = $this->makeFindPrefix($value, $options);	
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
	 * @see save()
	 * @see delete()
	 * @return boolean
	 */
	public function endTransaction()
	{
		if ((!self::$transactional) || (self::$queryCount === 0))
		{
			return true;
		}
		self::$transactional = false;	
		if (self::$queryErrors > 0) 
		{
			$queries = array_reverse(self::$queryLog);
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
					self::$rollbackLog[$i]['key'] = $query['key'];
					self::$rollbackLog[$i]['type'] = $type;
					self::$rollbackLog[$i]['data'] = $query['rollback'];
					self::$rollbackLog[$i]['result'] = $result;
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
     * @see makeFindPrefix()
     * @see Memcache
     * @see Memcache::get()
     * @see log_message()
     * @see isSerialized()
     * @see getFields()
     * @see updateData()
     * @param array $value Message data
     * @param array $options 
	 * 				[override] string Override the entire key, e.g. 'publictimeline'	
	 * 				[prefixName] string Override the first part of the prefix, the class name
	 * 				[prefixValue] string Override the the value of the key, e.g. 'email' instead of the default, id			
	 * @return mixed
     */
	public function find($value = null, $options = array())
	{
		$key = $this->makeFindPrefix($value, $options);
		try
		{
			// ob_start();
			$data = $this->mem->get($key);
			// ob_end_clean();
		}
		catch(Exception $e)
		{
			$this->log_message("error",$e);
			return null;
		}
		if ($this->isSerialized($data)) 
		{
			$fields = $this->getFields();
			$data = unserialize($data);
			$data = $this->updateData($fields, $data);
		}
		return $data;	
	}

	/**
	 * Get empty fields for the model
	 *
	 * @access public
	 * @return array
	 */
	public function getFields()
	{
		return $this->fields;
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
			set_error_handler(array('App_Model','unserialize_handler'));
			$return = ($str == serialize(false) || @unserialize($str) !== false);
			restore_error_handler();
			return $return;
		}
	}
	
	/**
	 * dummy error handler set up to obsorb any errors thrown by isSerialized
	 * @return 
	 * @param object $errno
	 * @param object $errstr
	 */
	function unserialize_handler($errno, $errstr)
	{
	   // don't do anything
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

	protected function makeFindPrefix($value = null, $options = array())
	{
		if (!isset($options['prefixName'])) 
		{
			$options['prefixName'] = $this->name;
		}
		if (!isset($options['prefixValue'])) 
		{
			$options['prefixValue'] = 'id';
		}
		if (!isset($options['override'])) 
		{
			$prefix = $options['prefixName'] . $this->prefixSeparator . $options['prefixValue'] . $this->prefixSeparator . $value;
		}		
		else 
		{
			$prefix = $options['override'];
		}		
		return $prefix;
	}
	
	protected function makeSavePrefix($data = array(), $options = array())
	{
		if (!isset($options['validate'])) 
		{
			$options['validate'] = false;
		}
		if (!isset($options['prefixName'])) 
		{
			$options['prefixName'] = $this->name;
		}
		if (!isset($options['prefixValue'])) 
		{
			$options['prefixValue'] = 'id';
		}		
		if (!isset($options['override'])) 
		{
			$value = null;
			if (isset($data[$options['prefixValue']])) 
			{
				$value = $data[$options['prefixValue']];
			}
			$prefix = $options['prefixName'] . $this->prefixSeparator . $options['prefixValue'] . $this->prefixSeparator . $value;
		}		
		else 
		{
			$prefix = $options['override'];
		}
		return $prefix;
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
	* Random Alpha-Numeric String
	*
	* @param int length
	* @return string 
	* @access public
	*/
	public function randomString($length) {
		$chars = array( 'a','b','c','d','e','f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A','B','C','D','E','F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
		return $this->_random($length, $chars);
	}
	
	/**
	* Random Alpha-Numeric String
	*
	* @param int length
	* @return int
	* @access public
	*/
	public function randomNum($length) {
		$chars = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
		return $this->_random($length, $chars);
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
		return self::$rollbackLog;
	}
	
    /**
     * Save a record
     *
     * @param array $data Message data
     * @param array $options 
	 * [validate] boolean Validate the save
	 * [override] string Override the entire key, e.g. 'publictimeline'	
	 * [prefixName] string Override the first part of the prefix, the class name
	 * [prefixValue] string Override the the value of the key, e.g. 'email' instead of the default, id
	 * [saveOnly] string Key from Data you wish to save if you don't want to save the whole array
	 * @return boolean
     */
	function save($data, $options = array()) 
	{
		$this->key = $this->makeSavePrefix($data, $options);
		if (!isset($options['validate'])) 
		{
			$options['validate'] = true;
		}
		if (isset($options['saveOnly'])) 
		{
			$data = $data[$options['saveOnly']];
		}
		$valid = true;
		$newData = null;	
		$this->modelData = $data;
		$this->logQuery($this->key, 'save');
		if ($options['validate']) 
		{
			$valid = $this->validate();
		}
		if ($valid) {
			$this->setUpTimestampFields();
			if (is_array($this->modelData)) 
			{
				$newData = serialize($this->modelData);
			}
			else 
			{
				$newData = $this->modelData;
			}
			if ($this->key) 
			{	
				try
				{
					// ob_start();
					$valid = $this->mem->set($this->key, $newData);
					// ob_end_clean();
				}
				catch(Exception $e)
				{
					$this->log_message("error",$e);
					//$this->flashMessage = $valid;
				}
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
	 * Set's time to modified and created fields
	 *
	 * @access public
	 * @return 
	 */
	private function setUpTimestampFields()
	{
		$now = time();
		if (is_array($this->modelData)) 
		{
			if (array_key_exists('created', $this->fields)) 
			{
				if (empty($this->modelData['created'])) 
				{
					$this->modelData['created'] = $now;
				}
			}
			if (array_key_exists('modified', $this->fields))
			{
				$this->modelData['modified'] = $now;
			}
		}
	}
	

	/**
	 * Transactional
	 */
	function startTransaction()
	{
		self::$transactional = true;
		self::$queryLog = array();
		self::$queryCount = 0;
		self::$queryErrors = 0;
		self::$rollbackLog = array();			
	}

	/**
	 * Show the query log
	 */
	function queryLog()
	{
		return self::$queryLog;
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

    function validates_numericality_of($fieldName,$options=array()) 
	{
			$fieldValue = $this->getValue($this->modelData, $fieldName);
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
                            $options['message'] = $fieldName . ' should be an integer.';
                    } else {
                            $options['message'] = $fieldName . ' should be a number.';
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
    }

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
			$record = self::find($fieldValue, array('prefixValue'=>$fieldName));
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