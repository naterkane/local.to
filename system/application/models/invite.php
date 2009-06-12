<?php
/**
 * Used to get invites
 *
 */
class Invite extends App_Model
{
	/**
	 * @var $table
	 */
	protected $table = 'invites';
	
	/**
	 * Flags an invitation record as having been accepted, the account associated with the attached email will be considered active.
	 * 
	 * @return 
	 * @param string $email
	 * @param string $key
	 */
	public function accept($key)
	{
		return $this->db->update($this->table, array('activated'=>'1'), array('key_hashed'=>md5($key)));
	}
	
	/**
	 * Creates an invitation record
	 * 
	 * @return 
	 * @param object $data
	 */
	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}
	
	/**
	 * Deletes an invitation record
	 * 
	 * @return 
	 * @param string $email
	 * @param string $key
	 */
	public function delete($email, $key)
	{
		return $this->db->delete($this->table, array('key'=>md5($key))); 
	}
	
	/**
	 * Retrieves an invitation record
	 * 
	 * @return 
	 * @param string $email[optional]
	 * @param string $key[optional]
	 */
	public function get($key = null)
	{
		$results = $this->db->get_where($this->table, array('key_hashed'=>md5($key), 'activated'=>'0'));
		if (!$results) 
		{
			return null;
		}
		$invites = $results->result_array();
		if (!isset($invites[0])) 
		{
			return null;
		}
		else
		{
			return $invites[0];			
		}
	}
	
	
}
?>