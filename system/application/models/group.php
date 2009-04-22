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
		$data['id'] = $this->makeId();
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
		return $this->push($this->prefixGroupMessages($group_id), $member_id);
	}
	
	/**
	 * Find a group by id
	 *
	 * @access public
	 * @param string $name
	 * @return array User data
	 */
	function get($group_id = null)
	{
		$return = null;
		if ($group_id) 
		{
			return $this->find($this->prefixGroup($group_id));
		}
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
			$group_id = $this->find($this->prefixGroupName($name), true);
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
	 * @param string $name Name of group
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
	 * Get Owner
	 *
	 * @access public
	 * @param string $name Name of group
	 * @return string Owner
	 */
	function getOwner($id)
	{
		$group = $this->find($id);
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
	 * Is a user an owner of a group
	 *
	 * @access public
	 * @param string $name group name
	 * @param string $user_name user to search for
	 * @return boolean
	 */
	function isOwner($id, $user_id)
	{
		return ($id == $user_id);
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
			$this->validates_uniqueness_of('name', array('message'=>'Group name has already been taken', 'fieldValue'=>$this->prefixGroupName($this->input->post('name'))));
			$this->validates_presence_of('name', array('message'=>'A group name is required'));			
		}
	    return (count($this->validationErrors) == 0);
	}
	
}
?>