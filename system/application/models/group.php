<?php
/**
* Group model
*/
class Group extends App_Model
{
	
	/**
	 * Add a group
	 *
	 * @access public
	 * @param array $data
	 * @return boolean
	 */
	function add($data = array(), $owner_id)
	{
		$data['members'] = array(0 => $owner_id);
		$data['messages'] = array();		
		$data['owner_id'] = $owner_id;
		$data['public'] = 1;
		$this->mode = 'add';
		return $this->save($data);
	}
	
	/**
	 * Add member to a group
	 *
	 * @access public
	 * @param string $name Name of group
	 * @param string $member Member to add
	 * @return boolean
	 */
	function addMember($name, $member)
	{
		if (!empty($member)) 
		{
			$group = $this->find($name);
			$group['members'][] = $member;
			return $this->save($group);
		} 
		else 
		{
			return true;
		}		
	}

	/**
	 * Group specific find
	 *
	 * @access public 
	 * @param group $name
	 * @return array
	 */
	function find($name)
	{		
		return parent::find($this->prefixGroup($name));
	}
	
	/**
	 * Get Members
	 *
	 * @access public
	 * @param string $name Name of group
	 * @return array Members
	 */
	function getMembers($name)
	{
		$group = $this->find($name);
		return $this->getValue($group, 'members');
	}
	
	/**
	 * Get Owner
	 *
	 * @access public
	 * @param string $name Name of group
	 * @return string Owner
	 */
	function getOwner($name)
	{
		$group = $this->find($name);
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
	function isMember($groupname, $username, $members = array())
	{
		if (empty($members)) 
		{
			$members = $this->getMembers($groupname);
		}
		if (is_array($members)) 
		{
			return in_array($username, $members);
		} 
		else 
		{
			return false;
		}
	}
	
	/**
	 * Is a user an owner of a group
	 *
	 * @access public
	 * @param string $name group name
	 * @param string $user_name user to search for
	 * @return boolean
	 */
	function isOwner($groupname, $user_name)
	{
		return ($groupname == $user_name);
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
	 * Remove member from a group
	 *
	 * @access public
	 * @param string $name Name of group
	 * @param string $member Member to add
	 * @return boolean
	 */
	function removeMember($groupname, $member)
	{
		if (!$this->isOwner($groupname, $member)) 
		{
			$group = $this->find($groupname);
			$new_lineup = array();
			foreach ($group['members'] as $previous_member) 
			{
				if ($previous_member != $member) 
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
	 * Group specific save
	 *
	 * @access public 
	 * @param array $group 
	 * @return boolean
	 */
	function save($group)
	{		
		return parent::save($this->prefixGroup($group['name']), $group);
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
		if ($this->mode == 'add')
		{
			$this->validates_format_of('name', array('with'=>ALPHANUM, 'message'=>'A group name may only be made up of numbers, letters, and underscores'));
			$this->validates_length_of('name', array('min'=>6, 'max'=>25, 'message'=>'A group name must be between 6 and 25 characters'));
			$this->validates_uniqueness_of('name', array('message'=>'Group name has already been taken'));
			$this->validates_presence_of('name', array('message'=>'A group name is required'));			
		}
	    return (count($this->validationErrors) == 0);
	}
	
}
?>