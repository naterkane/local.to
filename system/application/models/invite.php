<?php
/**
 * Used to get invites
 */
class Invite extends App_Model
{
	
	protected $table = 'invites';
	
	public function accept($email, $key)
	{
		return $this->db->update($this->table, array('activated'=>1), array('email'=>$email, 'key'=>$key));
	}
	
	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}
	
	public function delete($email, $key)
	{
		return $this->db->delete($this->table, array('email' => $email, 'key'=>$key)); 
	}
	
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