<?php
/**
* Group model
*/
class Group extends App_Model
{

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
		$data['id'] = $this->makeId($this->groupId);
		$data['owner_id'] = $owner_id;
		$data['public'] = 1;
		$this->mode = 'add';
		if ($this->save($this->prefixGroup($data['id']), $data)) 
		{
			$this->mode = null;
			$this->save($this->prefixGroupName($data['name']), $data['id'], false);
			$this->addMember($data['id'], $owner_id);
			$this->push($this->prefixGroupMessages($data['id']), null);
			return true;
		} 
		else 
		{
			return false;
		}
	}
	
	/**
	 * Add member to a group
	 *
	 * @access public
	 * @param string $name Name of group
	 * @param string $member Member to add
	 * @return boolean
	 */
	function addMember($group_id, $member_id = null)
	{
		return $this->push($this->prefixGroupMembers($group_id), $member_id);
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
	function getMembers($group_id)
	{
		$member_ids = $this->find($this->prefixGroupMembers($group_id));		
		$members = null;
		if ($member_ids) 
		{
			foreach ($member_ids as $member_id) {
				$members[] = $this->User->get($member_id);
			}
		}
		return $members;
	}
	
	/**
	 * Get Count of Members
	 *
	 * @access public
	 * @param int $group_id
	 * @return int
	 */
	function getMemberCount($group_id)
	{
		$member_ids = $this->find($this->prefixGroupMembers($group_id));
		if ($member_ids) 
		{
			return count($member_ids);
		} 
		else 
		{
			return null;
		}
		return $count;
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
	function isMember($id, $user_id, $members = array())
	{	
		if (empty($members)) 
		{
			$members = $this->getMembers($id);
		}
		if (is_array($members)) 
		{
			foreach ($members as $member) {
				if (!empty($member['id'])) 
				{
					if ($member['id'] == $user_id) 
					{
						return true;
					}
				}
			}
			return false;
		} 
		else 
		{
			return false;
		}
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
	function removeMember($group_id, $user_id)
	{
		if (!$this->isOwner($group_id, $user_id)) 
		{
			$group = $this->get($group_id);
			$new_lineup = array();
			$members = $this->find($this->prefixGroupMembers($group_id));
			foreach ($members as $previous_member) 
			{
				if ($previous_member != $user_id) 
				{
					$new_lineup[] = $previous_member;
				}
			}
			return $this->save($this->prefixGroupMembers($group_id), $new_lineup);
		} else {
			return false;
		}		
	}

	/**
	 * Send message to group message list
	 *
	 * @access public
	 * @param int $message_id
	 * @param int $group_id
	 * @return boolean
	 */
	function sendTo($group_id, $message_id)
	{
		return $this->push($this->prefixGroupMessages($group_id), $message_id);
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
		$groups = $this->Group->matchGroups($messageData['message']);
		if (!empty($groups)) 
		{
			foreach ($groups as $groupname) 
			{
				$group = $this->getByName($groupname);
				$this->sendTo($group['id'], $messageData['id']);
				if ($group) 
				{
					$members = $this->getMembers($group['id']);
					foreach ($members as $member) {
						if (!in_array($member['id'], $sent)) 
						{
							$this->push($this->prefixUserPublic($member['id']), $messageData['id']);
							$this->push($this->prefixUserPrivate($member['id']), $messageData['id']);
							$sent[] = $member['id'];
						}
					}
				}
			}
		}
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
		if ($this->save($this->prefixGroup($oldGroup['id']), $group)) 
		{
			$this->mode = null;			
			$this->delete($this->prefixGroupName($oldGroup['name']));
			$this->save($this->prefixGroupName($newGroup['name']), $oldGroup['id']);
			return true;
		} 
		else 
		{
			return false;
		}
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
			if ($this->mode == 'update') 
			{
				$this->validates_length_of('desc', array('min'=>0, 'max'=>160, 'message'=>'A description must be between 1 and 160 characters long'));
			}
		}
	    return (count($this->validationErrors) == 0);
	}
	
}
?>