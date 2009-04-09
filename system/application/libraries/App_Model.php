<?php
/**
* Extension of CI Model
*
* Allows for app-wide validation in the model and basic CRUD functionality
*  
*/
class App_Model extends Model {

	var $modelData = array();
	var $redisIncrement = 10;
	var $validationErrors = array();
	var $mode;

	function __construct()
	{
		$this->mem = new Memcache();
		$this->mem->connect('localhost', 11211) or die ("Could not connect");
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
			if (is_array($data)) {
				$data = serialize($this->modelData);
			}
			if ($this->find($key)) {
				$this->mem->replace($key, $data);				
			} else {
				$this->mem->add($key, $data);
			}
			return true;
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