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
	public function accept($email, $key)
	{
		return $this->db->update($this->table, array('activated'=>1), array('email'=>$email, 'key'=>$key));
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
		return $this->db->delete($this->table, array('email' => $email, 'key'=>$key)); 
	}
	
	/**
	 * Retrieves an invitation record
	 * 
	 * @return 
	 * @param string $email[optional]
	 * @param string $key[optional]
	 */
	public function get($email = null, $key = null)
	{
		$results = $this->db->get_where($this->table, array('email'=>$email, 'key'=>$key));
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