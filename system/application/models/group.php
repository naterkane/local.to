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
		$this->save($this->prefixGroup($data['name']), array(0 => $owner_id));
		$this->save($this->prefixGroupOwner($data['name']), $owner_id);	
		return true;
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
			$previous_members = $this->getMembers($name);
			$previous_members[] = $member;
			return $this->save($this->prefixGroup($name), $previous_members);
		} 
		else 
		{
			return true;
		}		
	}

	/**
	 * Get a group names from a message
	 *
	 * @access public
	 * @param string $message
	 * @return array
	 */
	function getGroups($message)
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
	 * Get Members
	 *
	 * @access public
	 * @param string $name Name of group
	 * @return array Members
	 */
	function getMembers($name)
	{
		return $this->find($this->prefixGroup($name));
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
		return $this->find($this->prefixGroupOwner($name));
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
	function isAMember($name, $user_name, $members = array())
	{
		if (empty($members)) 
		{
			$members = $this->getMembers($name);
		}
		if (is_array($members)) 
		{
			return in_array($user_name, $members);
		} 
		else 
		{
			return false;
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
	function removeMember($name, $member)
	{
		$owner = $this->getOwner($name);
		if ($member != $owner) 
		{
			$previous_members = $this->getMembers($name);
			$new_lineup = array();
			foreach ($previous_members as $previous_member) 
			{
				if ($previous_member != $member) 
				{
					$new_lineup[] = $previous_member;
				}
			}
			return $this->save($this->prefixGroup($name), $new_lineup);
		} else {
			return true;
		}		
	}	
	
}
?>