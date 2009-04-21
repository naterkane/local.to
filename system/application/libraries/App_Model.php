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
	private $prefixMessage = 'messages';
	private $prefixPublic = 'timeline';
	private $prefixSeparator = ':';	
	private $prefixUser = 'users';
	private $prefixUserPublic = 'userpublic';
	private $prefixUserPrivate = 'userprivate';
	public $action;
	public $id;	
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
		$this->tt->connect($host, $ci->config->item('tt_host_port'));
		unset($ci);
	}

	/**
	 * Delete a record
	 */
	function delete($key) 
	{
		return $this->tt->delete($key);
	}

	/**
	 * Find a record
	 *
	 * @todo This needs to behave differently depending on DB type selected. Right now does redis work which should move to redis library extension
	 * @access public
	 */
	function find($key) 
	{
		$data = $this->tt->get($key);
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
	 * Is a string serialized?
	 *
	 * @param string
	 * @return boolean 	
	 */
	function isSerialized($str) 
	{
	    return ($str == serialize(false) || @unserialize($str) !== false);
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
	function save($key, $data) 
	{
		$this->modelData = $data;
		if ($this->validate()) {
			if (is_array($this->modelData)) 
			{
				$data = serialize($this->modelData);
			}
			else 
			{
				$data = $this->modelData;
			}
			return $this->tt->put($key, $data);
		} else {
			return false;
		}
	}

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
	function validates_callback ($callback, $fieldName,$options=array()) 
	{
        if ( !isset($options['message']) ) 
		{
                $options['message'] = 'The field is incorrectly entered.';
        }		
		if (!call_user_func(array($this, $callback))) 
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

    function validates_format_of($fieldName, $options=array()) 
	{
		$fieldValue = $this->getValue($this->modelData, $fieldName);		
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
            if ( !isset($options['on']) ) 
			{
				$options['on'] = 'save';
            }
			$record = $this->find($fieldValue);
			if ($this->action != 'create') 
			{
				//non-create code here
			}
			if ($record) 
			{
				$this->validationErrors[$fieldName] = $options['message'];
			}
    }
	
}

?>