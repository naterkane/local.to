<?php
/**
* Group model
*/
class Group extends App_Model
{

	protected $idGenerator = 'groupId';

	/**
	 * Get all groups
	 */
	function getAllGroups(){
		echo $this->prefixGroup("all");
		return parent::find($this->prefixGroup("all"));
	}
	
	/**
	 * Add a group
	 *
	 * @access public
	 * @param array $data
	 * @return boolean
	 */
	function add($data = array(), $owner_id)
	{
		$data['id'] = $this->makeId($this->idGenerator);
		$data['owner_id'] = $owner_id;
		$data['public'] = 1;
		$data['members'] = array($owner_id);
		$data['messages'] = array();
		$owner = $this->User->get($owner_id);
		$data['time_zone'] = $owner['time_zone'];
		$this->mode = 'add';
		$this->startTransaction();
		$this->save($this->prefixGroup($data['id']), $data);
		$this->mode = null;
		$this->save($this->prefixGroupName($data['name']), $data['id'], false);
		return $this->endTransaction();
	}
	
	/**
	 * Add member to a group
	 *
	 * @access public
	 * @param string $name Name of group
	 * @param string $member Member to add
	 * @return boolean
	 */
	function addMember($group, $member_id = null)
	{
		array_unshift($group['members'], $member_id);
		return $this->save($this->prefixGroup($group['id']), $group);
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
			return $this->find($this->prefixGroup($group_id));
		}
	}
	
	/**
	 * Find all groups
	 *
	 * @access public
	 * @return array $groups
	 */
	function getAll($max = 2000)
	{
		$prefix = $this->prefixGroupName(null);
		$groups = $this->tt->fwmkeys($prefix, $max);
		sort($groups);
		$return = array();
		foreach ($groups as $key => $value) {
			$return[$key] = str_replace($prefix, '', $value);
		}
		return $return;
	}
	
	/**
	 * Find a group by name
	 *
	 * @access public
	 * @param string $name
	 * @return array User data
	 */
	function getByName($name = null)
	{
		$return = null;
		if ($name) 
		{
			$group_id = $this->find($this->prefixGroupName($name));
			if ($group_id) 
			{
				return $this->get($group_id);
			}
		}
	}
	
	/**
	 * Get Members
	 *
	 * @access public
	 * @param int $group_id Name of group
	 * @return array Members
	 */
	function getMembers($member_ids)
	{
		$members = array();
		foreach ($member_ids as $member_id) {
			$members[] = $this->User->get($member_id);
		}
		return $members;
	}
	
	/**
	 * Get Owner
	 *
	 * @access public
	 * @param string $name Name of group
	 * @return string Owner
	 */
	function getOwner($group_id)
	{
		$group = $this->find($this->prefixGroup($group_id));
		return $this->getValue($group, 'owner_id');		
	}
	
	/**
	 * Is a user a member of a group
	 *
	 * @access public
	 * @param string $name group name
	 * @param string $user_name user to search for
	 * @param array $members[optional] will use this array instead if supplied
	 * @return boolean
	 */
	function isMember($members, $user_id)
	{
		if (!is_array($members))
			return false;
			
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
	 * @access public
	 * @param int $user_id
	 * @param int $owner_id[optional]
	 * @param int $group_id[optional]
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
	 * Get a group names from a message
	 *
	 * @access public
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
	 * Is a group name unique?
	 *
	 * @access public
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
	 * Remove member from a group
	 *
	 * @access public
	 * @param string $name Name of group
	 * @param string $member Member to add
	 * @return boolean
	 */
	function removeMember($group, $user_id)
	{
		if (!$this->isOwner($group_id, $user_id)) 
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
			return $this->save($this->prefixGroup($group['id']), $group);
		} else {
			return false;
		}		
	}
	
	/**
	 * Send message to a group
	 *
	 * @todo Break this out into smaller methods
	 * @access public	
	 * @param array $data
	 * @param int $user_id	
	 * @return 
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
				array_unshift($group['messages'], $messageData['id']);				
				$this->save($this->prefixGroup($group['id']), $group);				
				if ($group) 
				{
					foreach ($group['members'] as $member_id) {
						if (!in_array($member_id, $sent)) 
						{
							$member = $this->User->get($member_id);
							array_unshift($member['private'], $messageData['id']);
							array_unshift($member['public'], $messageData['id']);
							$this->User->save($this->prefixUser($member_id), $member);
							$sent[] = $member_id;
						}
					}
				}
			}
			return $this->endTransaction();
		}
		return false;
	}

	/**
	 * Update group
	 *
	 * @access public
	 * @param array $oldGroup
	 * @param array $newGroup
	 * @param array $user_id	
	 * @return boolean
	 */
	function update($oldGroup, $newGroup, $user_id)
	{
		$this->mode = 'update';
		$group = $this->updateData($oldGroup, $newGroup);
		$this->startTransaction();
		$this->save($this->prefixGroup($oldGroup['id']), $group);
		$this->mode = null;			
		$this->delete($this->prefixGroupName($oldGroup['name']));
		$this->save($this->prefixGroupName($newGroup['name']), $oldGroup['id']);
		return $this->endTransaction();		
	}	
	
	/**
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
				$this->validates_length_of('desc', array('min'=>0, 'max'=>160, 'message'=>'A description must be between 1 and 160 characters long'));
			}
		}
	    return (count($this->validationErrors) == 0);
	}
	
}
?>