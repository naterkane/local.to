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
	 * Set to true if a message is save to a group
	 * @access public
	 * @var boolean
	 */
	public $keepOffPublicTimeline = false;
	
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
			$this->save($group, array('prefixValue'=>'fullname', 'saveOnly'=>'id', 'validate'=>false));
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
		//if user is not already a member and is not blacklisted
		if ((!in_array($member['id'], $group['members'])) && (!in_array($member['id'], $group['blacklist'])))
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
	 * Add to blacklist
	 *
	 * @access public
	 * @param array $group 
	 * @param array $user_id 
	 * @return
	 */
	public function addToBlacklist(&$group, $user_id)
	{
		$this->addTo('blacklist', $group, $user_id);
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
		return $this->saveGroupList($groups);
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
	 * Add to messages
	 *
	 * @access public
	 * @param array $group 
	 * @param integer $message_id 
	 */
	public function addToMessages(&$group, $message)
	{
		if (!$message['reply_to']) 
		{
			array_unshift($group['messages_threaded'], $message['id']);
		}
		array_unshift($group['messages'], $message['id']);
		$this->save($group);
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
	 * Is a group's full name unique?
	 *
	 * @return boolean
	 */
	function fullNameUnique()
	{
		if (isset($this->modelData['fullname'])) 
		{
			$group = $this->getByFullName($this->modelData['fullname']);
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
		$groups = $this->find(null, array('override'=>'groups', 'ignoreModelFields'=>true));
		if (empty($groups['all'])) 
		{
			$groups['all'] = array();
		}
		return $groups;
	}

	/**
	 * Find a group by full name
	 *
	 * @param string $fullname
	 * @return array Group data
	 */
	function getByFullName($fullname = null)
	{
		$return = null;
		if ($fullname) 
		{
			$group_id = $this->find($fullname, array('prefixValue'=>'fullname'));
			if ($group_id) 
			{
				return $this->get($group_id);
			}
		}
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
		$groupnames = $this->clip($groupnames, $start, $end);
        $return = array();
		if (($groupnames) AND (is_array($groupnames))) {
			foreach ($groupnames as $name)
	        {
				if ($name) 
				{
					$group = $this->getByName($name);
					if ($group) 
					{
						$return[] = $group;
					}
				}
	        }
		}
        return $return;
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
    public function getManyByIds($group_ids = array(), $start = null, $end = null)
    {
		$group_ids = $this->clip($group_ids, $start, $end);
        $return = array();
		if (($group_ids) AND (is_array($group_ids))) {
			foreach ($group_ids as $group_id)
	        {
				$group = $this->get($group_id);
				if ($group) 
				{
					$return[] = $group;
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
	 * Is the user a member of any of the groups?
	 *
	 * @access public
	 * @param array $groups Array of group ids
	 * @param int $user_id User id in question	
	 * @return boolean
	 */
	public function isMemberOfAnyGroup($groups, $user_id)
	{
		if (!is_array($groups)) 
		{
			return false;
		}
		foreach ($groups as $group_id) {
			$group = $this->get($group_id);
			if (!empty($group['members'])) 
			{
				if ($this->isMember($group['members'], $user_id)) 
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
	 * Is group public
	 *
	 * @access public
	 * @param array $group
	 * @return false 
	 * @todo Make an actual function when we start using public groups 	
	 */
	public function isPublic($group = array())
	{
		return false;
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
	 * @param array $group
	 * @param integer $user_id Member to remove
	 * @return boolean
	 */
	function removeMember(&$group, $user_id)
	{
		if (!$this->isOwner($user_id, $group['owner_id'])) 
		{
			$group['members'] = $this->Group->removeFromArray($group['members'], $user_id);
			return $this->save($group);
		} else {
			return false;
		}		
	}
	
	/**
	 * Remove a user from a blacklist
	 *
	 * @param array $group 
	 * @param integer $user_id Member to remove
	 * @return boolean
	 */
	function removeFromBlacklist(&$group, $user_id)
	{
		$group['blacklist'] = $this->Group->removeFromArray($group['blacklist'], $user_id);
		return $this->save($group);		
	}
	
	/**
	 * Remove a name from the group list
	 *
	 * @access public
	 * @param string $name
	 * @return 
	 */
	public function removeFromGroupList($name = null)
	{
		if (!$name) 
		{
			return;
		}
		$groups = $this->getAll();			
		$groups['all'] = array_flip($groups['all']);
		unset($groups['all'][$name]);
		$groups['all'] = array_unique($groups['all']);
		$groups['all'] = array_flip($groups['all']);
		return $this->saveGroupList($groups);
	}
	
	
	/**
	 * Save the group list
	 *
	 * @access public
	 * @param array $group
	 * @return 
	 */
	public function saveGroupList($groups = array())
	{
		return $this->save($groups, array('override'=>'groups', 'validate'=>false, 'ignoreTime'=>true, 'ignoreModelFields'=>true));
	}
	
	
	/**
	 * Send a message to the members of a group if member, add to mentions if not. Checks to see if group exists as well. Does nothing if there is no mention of a group
	 *
	 * @todo Break this out into smaller methods
	 * @access public	
	 * @param array @messageData
	 * @param integer $user_id	
	 * @return boolean
	 */
	function sendTo($messageData, $user_id)
	{
		$this->mode = null;
		$sent = array();
		if (!empty($messageData['to_group'])) 
		{
			$this->startTransaction();
			$group = $this->getByName($messageData['to_group']);
			if ($this->isMember($group['members'], $user_id))
			{	
				$this->keepOffPublicTimeline = true;
				if ($this->sendToMembers($messageData, $group['members'], $user_id)) 
				{
					$this->addToMessages($group, $messageData);
				}
			}
			return $this->endTransaction();
		}
		return false;
	}
	
	/**
	 * Send to a list of members
	 *
	 * @access public
	 * @param array $message
	 * @param array $member_ids	
	 * @return boolean True if any members receive, false if none receive
	 */
	private function sendToMembers($message = array(), $member_ids = array(), $user_id = null)
	{
		if (empty($message) || empty($member_ids) || !$member_ids) 
		{
			return false;
		}
		$sent = array();
		foreach ($member_ids as $member_id) {
			if (!in_array($member_id, $sent)) 
			{
				$member = $this->User->get($member_id);
				$this->User->addToPrivate($member, $message);
				$sent[] = $member_id;
			}
		}
	    return (count($sent) > 0);
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
			$this->removeFromGroupList($oldGroup['name']);
			$this->addToGroupList($group['name']);			
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
			$this->validates_length_of('fullname', array('min'=>1, 'max'=>50, 'message'=>'A full group name must be between 1 and 50 characters'));
			$this->validates_callback('fullNameUnique', 'fullname', array('message'=>'Group full name has already been taken'));
			$this->validates_presence_of('fullname', array('message'=>'A full group name is required'));
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