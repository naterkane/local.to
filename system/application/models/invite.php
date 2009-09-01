<?php
/**
 * Nomcat
 *
 * An open source microsharing platform built on CodeIgniter
 *
 * @package		Nomcat
 * @author		NOM
 * @copyright	Copyright (c) 2009, NOM llc.
 * @license		http://creativecommons.org/licenses/by-sa/3.0/
 * @link		http://getnomcat.com
 * @version		$Id$
 * @filesource
 */
/**
 * Invite Model
 * 
 * Used to get invites
 * 
 * @package 	Nomcat
 * @subpackage	Models
 * @category	Model
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Invite extends App_Model
{
	/**
	 * @access protected
	 * @var string
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
	 * @return object results
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
	 * @param string $key[optional]
	 * @return null|array
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