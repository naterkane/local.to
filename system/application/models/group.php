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
 * Group Model
 * 
 * @package 	Nomcat
 * @subpackage	Models
 * @category	Model
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Group extends App_Model
{
	
	/**
	 * @access protected
	 * @var array
	 */
	protected $fields = array();
	
	/**
	 * @access protected
	 * @var string
	 */
	protected $name = 'Group';
	
	/**
	 * @access private
	 * @var string
	 */
	protected $idGenerator = 'groupId';
	
	/**
	 * Calls the parent constructor then loads any fields defined in the current theme's configuration into the Group::$fields array
	 */
	public function __construct()
	{
		parent::__construct();
		$this->ci = get_instance();
		if (file_exists(APPPATH . 'views/themes/' . $this->ci->config->item('theme') . '/config/group_fields.php')) 
		{
			require_once(APPPATH . 'views/themes/' . $this->ci->config->item('theme') . '/config/group_fields.php');
			$this->fields = $fields;
		}
		unset($this->ci);		
	}
	
	/**
	 * Add a group
	 *
	 * @param array $data
	 * @param integer $owner
	 * @return boolean
	 */
	function add($data = array(), $owner)
	{
		$group = $this->create($data);
		$group['id'] = $this->makeId($this->idGenerator);
		$group['owner_id'] = $owner['id'];
		$group['members'][] = $owner['id'];
		$group['time_zone'] = $owner['time_zone'];
		$this->mode = 'add';
		$this->startTransaction();
		if ($this->save($group)) 
		{
			$this->save($group, array('prefixValue'=>'name', 'saveOnly'=>'id', 'validate'=>false));
			$this->addToGroupList($group['name']);			
			$this->User->addGroup($owner, $group['id']);			
		}
		return $this->endTransaction();
	}
	
	/**
	 * Add User to a Group
	 * 
	 * @param array $name group object
	 * @param array $member user object
	 * @return boolean
	 */
	function addMember(&$group, &$member)
	{
		if (!in_array($member['id'], $group['members'])) 
		{
			array_unshift($group['members'], $member['id']);
			return $this->save($group);			
		}
		else 
		{
			return false;
		}
	}
	
	/**
	 * Add to group message
	 *
	 * @access public
	 * @param string $name
	 * @return boolean
	 */
	public function addToGroupList($name = null)
	{
		if (!$name) 
		{
			return false;
		}
		$groups = $this->getAll();
		$groups['all'][] = $name;
		sort($groups['all']);
		return $this->save($groups, array('override'=>'groups', 'validate'=>false));
	}
	
	/**
	 * Add to inbox
	 *
	 * @access public
	 * @param array $group 
	 * @param array $message_id 
	 * @return
	 */
	public function addToInbox(&$group, $message_id)
	{
		$this->addTo('inbox', $group, $message_id);
	}

	/**
	 * Add to mentions
	 *
	 * @access public
	 * @param array $group_mention 
	 * @param integer $message_id 
	 */
	public function addToMentions(&$group_mention, $message_id)
	{
		$this->addTo('mentions', $group_mention, $message_id);
	}
	
	/**
	 * Add to mentions
	 *
	 * @access public
	 * @param array $group 
	 * @param integer $message_id 
	 */
	public function addToMessages(&$group, $message_id)
	{
		$this->addTo('messages', $group, $message_id);
	}
	
	/**
	 * Add group dms to member inboxes
	 *
	 * @access public
	 * @param array $members An array of user ids
	 * @param mixed $sender
	 * @param array $message	
	 */
	public function dm($members = array(), $sender, $message)
	{		
		foreach ($members as $member) {
			$user = $this->User->get($member);
			if ($user) 
			{
				$this->User->addToInbox($user, $message['id']);
				$this->User->sms($user, $sender, $message['message']);
			}
		}
	}
	
	/**
	 * Find a group by id
	 *
	 * @access public
	 * @param string $name
	 * @return array Group data
	 */
	function get($group_id = null)
	{
		if ($group_id) 
		{
			return $this->find($group_id);
		}
	}
	
	/**
	 * Find all groups
	 *
	 * @return array $group_ids
	 */
	function getAll()
	{
		$groups = $this->find(null, array('override'=>'groups'));
		if (empty($groups['all'])) 
		{
			$groups['all'] = array();
		}
		return $groups;
	}
	
	/**
	 * Find a group by name
	 *
	 * @param string $name
	 * @return array User data
	 */
	function getByName($name = null)
	{
		$return = null;
		if ($name) 
		{
			$group_id = $this->find($name, array('prefixValue'=>'name'));			
			if ($group_id) 
			{
				return $this->get($group_id);
			}
		}
	}
	
    /**
     * Get more than one group
     *
	 * @access public
     * @param array $groupnames
     * @param integer $start[optional]
     * @param integer $end[optional]
     * @return array an array of groups
     */
    public function getMany($groupnames = array(), $start = null, $end = null)
    {
		if (($start !== null) && ($end !== null) && (is_array($groupnames)))
		{
			$groupnames = array_slice($groupnames, $start, $end);	
		}
        $return = array();
		if (($groupnames) AND (is_array($groupnames))) {
			foreach ($groupnames as $name)
	        {
				if ($name) 
				{
					$return[] = $this->getByName($name);
				}
	        }
		}
        return $return;
    }
	
	/**
	 * Get Members
	 *
	 * @param integer $group_id Name of group
	 * @return array Members
	 */
	function getMembers($member_ids)
	{
		$members = array();
		foreach ($member_ids as $member_id) {
			$member = array();
			$member = $this->User->get($member_id);
			$member['password'] = null;
			$member['passwordconfirm'] = null;			
			$member['realname'] = (!empty($member['realname']))? $member['realname'] : $member['username'];	
			$member['friend_status'] = $this->User->getFriendStatus($member, $this->userData);
			$members[] = $member;
		}		
		return $members;
	}
	
	/**
	 * Get's the owner of a group by the group's Id
	 *
	 * @param integer $group_id Name of group
	 * @return string Owner
	 */
	function getOwner($group_id)
	{
		$group = $this->find($group_id);
		return $this->getValue($group, 'owner_id');		
	}
	
	/**
	 * Is a user a member of a group
	 *
	 * @param array $members array of a group's members
	 * @param integer $user_id the Id of the user to search for
	 * @return boolean
	 */
	function isMember($members, $user_id)
	{
		if (!is_array($members)){
			return false;			
		}
		foreach ($members as $member_id) {
			if (!empty($member_id)) 
			{
				if ($member_id == $user_id) 
				{
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	 * Is a user an owner of a group?
	 *
	 * @param integer $user_id
	 * @param integer $owner_id[optional]
	 * @param integer $group_id[optional]
	 * @return boolean
	 */
	function isOwner($user_id, $owner_id = null, $group_id = null)
	{
		if (!$owner_id) 
		{
			$owner_id = $this->getOwner($group_id);
		}
		return ($owner_id == $user_id);
	}

	/**
	 * function matchGroups
	 * 
	 * Get a group names from a message
	 *
	 * @param string $message
	 * @return array
	 */
	function matchGroups($message)
	{
		preg_match_all(GROUP_MATCH, $message, $groups);
		if (isset($groups[2])) 
		{
			return $groups[2];
		} 
		else 
		{
			return array();
		}
	}

	/**
	 * Is a group's name unique?
	 *
	 * @return boolean
	 */
	function nameUnique()
	{
		if (isset($this->modelData['name'])) 
		{
			$group = $this->getByName($this->modelData['name']);
			if ((!$group['id']) OR ($this->modelData['id'] == $group['id']))
			{
				return true;
			}
			else 
			{
				return false;
			}
		} 
		else 
		{
			return true;
		}
	}

	/**
	 * Remove a user from a group
	 *
	 * @param array $group Object of group's data
	 * @param integer|string $user_id Member to remove
	 * @return boolean
	 */
	function removeMember($group, $user_id)
	{
		if (!$this->isOwner($group['id'], $user_id)) 
		{
			$new_lineup = array();
			foreach ($group['members'] as $previous_member) 
			{
				if ($previous_member != $user_id) 
				{
					$new_lineup[] = $previous_member;
				}
			}
			$group['members'] = $new_lineup;
			return $this->save($group);
		} else {
			return false;
		}		
	}
	
	/**
	 * Send a message to the members of a group
	 *
	 * @todo Break this out into smaller methods
	 * @access public	
	 * @param array @messageData
	 * @param integer $user_id	
	 * @return boolean
	 */
	function sendToMembers($messageData, $user_id)
	{
		$this->mode = null;
		$sent = array();
		$sent[] = $user_id;
		$groups = $this->matchGroups($messageData['message']);
		if (!empty($groups)) 
		{
			$this->startTransaction();
			foreach ($groups as $groupname) 
			{
				$group = $this->getByName($groupname);
								
				if ($group && !empty($group['members'])) 
				{
					array_unshift($group['messages'], $messageData['id']);				
					$this->save($group);
					
					/**
					 * check to make sure that the user sending the message is a member of the group before posting to group member's feeds.
					 */
					if (in_array($user_id,$group['members']))
					{
						foreach ($group['members'] as $member_id) {
							if (!in_array($member_id, $sent)) 
							{
								$member = $this->User->get($member_id);
								array_unshift($member['private'], $messageData['id']);
								$this->User->save($member);
								$sent[] = $member_id;
							}
						}	
					}
					else{
					/**
					 * @todo if the posting user isn't a member of a group, add this message to group's "mentions"
					 */
					}
				}
				
			}
			return $this->endTransaction();
		}
		return false;
	}

	/**
	 * function update
	 * 
	 * Update group
	 *
	 * @access public
	 * @param array $oldGroup
	 * @param array $newGroup
	 * @param integer $user_id	
	 * @return boolean
	 */
	function update($oldGroup, $newGroup, $user_id)
	{
		$this->mode = 'update';
		$group = $this->updateData($oldGroup, $newGroup);
		$this->startTransaction();
		if ($this->save($group)) 
		{
			$this->delete($oldGroup['name'], array('prefixValue'=>'name'));
			$this->save($group, array('prefixValue'=>'name', 'saveOnly'=>'id', 'validate'=>false));
		}
		
		$this->mode = null;			
		return $this->endTransaction();		
	}	
	
	/**
	 * function validate
	 * 
	 * Validates a group
	 *
	 * @access public
	 * @return boolean
	 */	
	function validate()
	{
		$this->setAction();	
		if (($this->mode == 'add') || ($this->mode == 'update'))
		{
			$this->validates_callback('isNotReserved', 'name', array('message'=>'This is a reserved name'));			
			$this->validates_format_of('name', array('with'=>ALPHANUM, 'message'=>'A group name may only be made up of numbers, letters, and underscores'));
			$this->validates_length_of('name', array('min'=>1, 'max'=>15, 'message'=>'A group name must be between 1 and 15 characters'));
			$this->validates_callback('nameUnique', 'name', array('message'=>'Group name has already been taken'));
			$this->validates_presence_of('name', array('message'=>'A group name is required'));			
			$this->validates_format_of('email', array('with'=>VALID_EMAIL, 'message'=>'A valid email is required', 'allow_null'=>true));
			if ($this->mode == 'update') 
			{
				$this->validates_format_of('other_email', array('with'=>VALID_EMAIL, 'message'=>'A valid email is required', 'allow_null'=>true));				
				$this->validates_length_of('sport', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('level', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('gender', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('location', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('home_field', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('league', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('division', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('session_start', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('session_end', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('recent_news', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('other_email', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('url', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));				
				$this->validates_length_of('address', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('address_line2', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('state', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('city', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('postal_code', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('country', array('min'=>0, 'max'=>520, 'message'=>'Must be fewer than 500 characters'));
				$this->validates_length_of('desc', array('min'=>0, 'max'=>160, 'message'=>'A description must be between 1 and 160 characters long'));
			}
		}
	    return (count($this->validationErrors) == 0);
	}
	
}
?>