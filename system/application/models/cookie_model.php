<?php
/**
 * Used to save cookies to db
 */
class Cookie_model extends App_Model
{
	protected $name = 'Session';
	
	public function validate()
	{
		$this->setAction();			
		$this->validates_presence_of('id', array('message'=>'A session id is required'));
	    return (count($this->validationErrors) == 0);		
	}
	
}
?>