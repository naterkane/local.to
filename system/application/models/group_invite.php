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
 * Group Invites
 * 
 * Used to save cookies to db
 * 
 * @package 	Nomcat
 * @subpackage	Models
 * @category	Model
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Group_Invite extends App_Model
{
	/**
	 * @access protected
	 * @var array
	 */
	protected $fields = array(
			/**
			 * Has the key been activated?
			 * @var boolean
			 */
			'activated' => false,
			/**
			 * Time the key was activated by User
			 * @var string|boolean|datetime
			 */
			'activated_time' => null,
			/**
			 * Time invite was created
			 * @var string|datetime
			 */	
			'created' => null,
			/**
			 * User's email
			 * @var string
			 */
			'email' => null, 
			/**
			 * ID of group user is invited to
			 * @var integer
			 */
			'group_id' => null, 
			/**
			 * Unhashed / raw key
			 * @var string
			 */		
			'key' => null, 
			/**
			 * Hashed key
			 * @var string
			 */
			'key_hashed' => null 
			);	
	/**
	 * @access protected
	 * @var string
	 */
	protected $name = 'group_invite';	
	/**
	 * @var array
	 */		
	public $failures = array();	
	/**
	 * @var string
	 */
	public $message;
	/**
	 * @var array
	 */
	public $results = array();
	/**
	 * @var array
	 */
	public $successes = array();
	
	/**
	 * Calls parent constructor, then passes off Group from controller instance
	 *
	 * @access public
	 */
	public function __construct()
	{
		parent::__construct();
		$ci = get_instance();
		$this->Group = $ci->Group;
		unset($ci);
	}
	
	/**
	 * Add a new invite
	 *
	 * @access public
	 * @param array $data Passed by reference
	 * @param string $email
	 * @param array $group Passed by reference 
	 * @return boolean|Group
	 */
	public function add(&$data = array(), $email, &$group)
	{
		$data = $this->create($data);
		$data['group_id'] = $group['id'];
		$data['email'] = $email;
		$data['key'] = $this->randomString(10);
		$data['key_hashed'] = md5($data['key']);
		if (!$this->save($data, array('prefixValue'=>'key_hashed'))) 
		{
			return false;
		}
		array_unshift($group['invites'], $data['key']);
		return $this->Group->save($group);
	}

	/**
	 * Add Many invites
	 * 
	 * @access public
	 * @param string $emails
	 * @param array $group data (passed by reference)
	 */
	public function addMany($emails, &$group)
	{
		if (!is_array($emails)) 
		{
			str_replace(' ','',$emails);
			$emails = explode(',', $emails);
		}
		$return['successes'] = 0;
		$return['failures'] = 0;		
		foreach ($emails as $key => $email) {
			$this->validationErrors = array();
			$email = trim($email);
			$data = array();
			if ($this->add($data, $email, $group)) 
			{
				$this->successes[] = $data;
			} 
			else 
			{
				$this->failures[] = $email;
			}
		}
		$this->makeResponse();
	}
	
	/**
	 * Delete a key
	 *
	 * @access public
	 * @param string $key
	 * @param boolean $checkOwnership Check to see whether the user deleting owns the invite
	 * @return boolean
	 */
	public function delete($key = null, $checkOwnership = true)
	{
		$invite = $this->get($key);
		if (!$key && !$invite) 
		{
			return false;
		}		
		$group = $this->Group->get($invite['group_id']);		
		if ($checkOwnership) 
		{
			if (!$this->Group->isOwner($group['id'], $this->userData['id'])) 
			{
				return false;
			}
		}
		if (parent::delete($invite['key_hashed'], array('prefixValue'=>'key_hashed'))) 
		{
			$group['invites'] = $this->removeFromArray($group['invites'], $invite['key']);
			return $this->Group->save($group);
		}
	}
	
	/**
	 * Get one invite
	 *
	 * @access public
	 * @param integer $key
	 * @return array Full invite
	 */
	public function get($key)
	{
		$key_hashed = md5($key);
		return $this->find($key_hashed, array('prefixValue'=>'key_hashed'));
	}
	
	/**
	 * Get many invites
	 *
	 * @access public
	 * @param array $keys Array of invite keys
	 * @return array Full invites
	 */
	public function getMany($keys = array())
	{
		$return = array();
		foreach ($keys as $key) {
			$return[] = $this->get($key);
		}
		return $return;
	}
	
	/**
	 * Make a response after adding many invites
	 *
	 * @access public
	 * @return	
	 */
	public function makeResponse()
	{
		if (!empty($this->successes)) 
		{
			$this->message .= count($this->successes) . ' invites(s) added. ';
		}
		if (!empty($this->failures)) 
		{
			if ($this->failures[0]) 
			{
				$this->message .= count($this->failures) . ' email(s) not added. Emails not added: ' . implode(', ', $this->failures) . '. ';
			}
			else 
			{
				$this->message .= 'You must provide an email address.'; 
			}
		}
	}
	
	/**
	 * Validate invite saves
	 *
	 * @access public
	 * @return boolean
	 */
	public function validate()
	{
		$this->validates_format_of('email', array('with'=>VALID_EMAIL, 'message'=>'A valid email is required'));
	    return (count($this->validationErrors) == 0);
	}
	
}
?>