<?php
/**
* SMS key
*/
class Sms_key extends App_Model
{

	protected $fields = array(
			'created' => null, //Timestamp of time created [int]		
			'key' => null, //Random string [string]
			'user_id' => null //User's id [string]
			);
	protected $name = 'Sms_key';
	public $key;	

	public function delete($user_id)
	{
		return parent::delete($user_id, array('prefixValue'=>'user_id'));
	}

	public function find($user_id)
	{
		return parent::find($user_id, array('prefixValue'=>'user_id'));
	}

	public function save($data = array())
	{
		return parent::save($data, array('prefixValue'=>'user_id'));
	}

}
?>