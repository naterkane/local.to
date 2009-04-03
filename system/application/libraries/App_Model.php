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

	/**
	 * Delete a record
	 */
	function delete($key) {
		return $this->redis->delete($key);
	}

	/**
	 * Find a record
	 *
	 * @todo This needs to behave differently depending on DB type selected. Right now does redis work which should move to redis library extension
	 * @access public
	 */
	function find($key) {
		$data = $this->redis->get($key);
		$this->redis->disconnect();	
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
			$this->redis->set($key, $data);
			$this->redis->disconnect();
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