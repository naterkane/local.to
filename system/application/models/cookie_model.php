<?php
/**
 * Used to save cookies to db
 */
class Cookie_model extends App_Model
{
	protected $fields = array(
			'id' => null, //Random string [string]
			'ip' => null, //User's ip [string]			
			'last_accessed' => null, //Timestamp of time last accessed [int]
			'user_agent' => null //User's browser, operating system [string]			
			);
	protected $name = 'Session';
	
	public function validate()
	{
		$this->setAction();			
		$this->validates_presence_of('id', array('message'=>'A session id is required'));
	    return (count($this->validationErrors) == 0);		
	}
	
}
?>