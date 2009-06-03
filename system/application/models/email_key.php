<?php
/**
* Email key
*/
class Email_key extends App_Model
{
	
	protected $fields = array(
			'created' => null, //Timestamp of time created [int]		
			'id' => null, //Random string [string]
			'id_unhashed' => null, //Unhashed version of id [string]
			'user_id' => null //User's id [string]
			);
	protected $name = 'Email_key';

}
?>