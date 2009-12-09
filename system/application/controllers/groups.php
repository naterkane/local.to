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
 * Groups
 * 
 * @package 	Nomcat
 * @subpackage	nomcat-controllers
 * @category	controller
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Groups extends App_Controller
{

	/**
	 * Check to see whether a group sidebar is to be set. 
	 * Most group views show sidebar, so set to true. Override in controller methods if otherwise.
	 * @access public
	 * @var boolean
	 */
	public $loadGroupSidebar = true;	

	/**
	 * Accept an invite
	 *
	 * @access public
	 * @param string $key
	 * @return 
	 */
	public function accept($key = null)
	{
		if ($key == null) $this->redirect("/groups");
		$this->load->model(array('Group_Invite'));
		$invite = $this->Group_Invite->get($key);
		$group = $this->Group->get($invite['group_id']);		
		if (empty($invite) || empty($group)) 
		{
			$this->redirect('/home','Sorry but that invite has already been accepted.','error');
		}
		$this->data['page_title'] = "Join " . ucwords($group['fullname']);
		if (empty($this->userData)) 
		{	
	        $this->layout = 'public';
			$this->data['page_title'] = 'Sign Up';			
			$this->data['key'] = $key;
			$this->data['sendMeHere'] = true;
			if ($this->postData) 
			{
	            if ($this->User->signUp($this->postData))
	            {
					$user = $this->User->get($this->User->insertId);
					$this->Group->addMember($group, $user);
					$this->User->addGroup($user, $group['id']);
					$this->Group_Invite->delete($invite['key'], false);
					$this->userData = $user;
					$this->userData['followers'] = array();
					$message = 'I just became a member of !'. $group['name'];
					$message_id = $this->Message->add(array('message'=>$message), $user);
					$this->User->addToPrivate($user, $this->Message->getOne($message_id));
					$this->mail->sendWelcome($user);
	                $this->redirect('/signin?redirect=' . urlencode('/group/' . $group['name']), 'Your account has been created. Please sign in.');
	            }
				else 
				{
					$this->setErrors(array('User'));
	                $this->cookie->setFlash('There was an error signing up. Please see below for details.', 'error');
				}
			}
			$this->data['email'] = $invite['email'];
			$this->load->view('groups/accept', $this->data);
		}
		else 
		{
			$group = $this->Group->get($invite['group_id']);
			if ($this->Group->isMember($group['members'], $this->userData['id']))
			{
				$this->redirect('/group/' . $group['name'], 'You are already a member of this '.$this->config->item('group').'.');
			}
			if ($this->Group->addMember($group, $this->userData)) 
			{
				$this->User->addGroup($this->userData, $group['id']);
				$this->Group_Invite->delete($invite['key'], false);
				$message = 'I just became a member of !'. $group['name'];
				$message_id = $this->Message->add(array('message'=>$message), $this->userData);
				$this->User->addToPrivate($this->userData, $this->Message->getOne($message_id));
				$this->redirect('/group/' . $group['name'], 'Welcome to ' . $group['name'] . '!');
			}
			else 
			{
				$this->redirect('/home', 'There was a problem joining this '.$this->config->item('group').'');
			}
		}
	}

	/**
	 * Add a new group
	 *
	 * @access public
	 * @return 
	 */
	public function add()
	{
		$this->mustBeSignedIn();
		$this->data['page_title'] = 'Add a ' . $this->config->item('group');
		if ($this->postData) {
			if ($this->Group->add($this->postData, $this->userData)) {
				$this->redirect('/groups/settings/' . $this->postData['name'],"Your ".$this->config->item('group')." has been created. Please fill out your ".$this->config->item('group')."'s info.");
			} else {
				$this->setErrors(array('Group'));
				$this->cookie->setFlash('There was an error adding your '.$this->config->item('group').'. Please see below for details.', 'error');
			}
		} 
		$this->loadGroupSidebar = false;		
		$this->load->view('groups/add', $this->data);
	}

	/**
	 * Avatar management
	 *
	 * @access public
	 * @param string $groupname
	 * @return
	 */
	public function avatar($groupname = null)
	{
		$this->mustBeSignedIn();
        $this->data['page_title'] = 'Upload Avatar';		
		if (!$group){
				$this->redirect("/groups");
		}
		$this->data['page_title'] = ucwords($group['fullname']);
		$this->data['group'] = $group;
		$this->data['group']['is_owner'] = $this->Group->isOwner($this->userData['id'], null, $this->data['group']['id']);
		$this->data['group']['im_a_member'] = in_array($this->userData['id'], $this->data['group']['members']);	
		$this->data['avatartype'] = 'Group';
		$this->data['avatarid'] = $this->data['group'];
		if ((!$this->data['group']) || (!$this->Group->isOwner($this->userData['id'], $this->data['group']['owner_id'])))
		{
			$this->redirect('/groups');
		}
		$this->_avatar($this->data['group']['id'], $this->data['group']['id'], 'group');
	}

	/**
	 * View blacklist
	 *
	 * @param string $groupname
	 * @param string $sidebar	
	 * @access public	
	 * @return 
	 */
	public function blacklist($groupname = null, $sidebar = null)
	{
		$this->mustBeSignedIn();
		$group = $this->Group->getByName($groupname);
		if (!$group){
				$this->redirect("/groups");
		}
		$this->data['page_title'] = ucwords($group['fullname']);
		$this->mustBeOwner($group);
		$this->data['group'] = $group;
		$this->data['page_title'] = $group['name'] . ' Blacklist';
       	$this->data['blacklist'] = Page::make('Group', $group['blacklist'], array('method'=>'getMembers'));
		$this->data['group']['is_owner'] = $this->Group->isOwner($this->userData['id'], $group['owner_id']);
		$this->data['group']['member_count'] = count($group['members']);			
		$this->data['group']['im_a_member'] = $this->Group->isMember($group['members'], $this->userData['id']);
		$this->load->view('groups/blacklist', $this->data);	
	}

	/**
	 * Delete an invite
	 *
	 * @access public
	 * @param string $key
	 * @return 
	 */
	public function deleteinvite($key = null)
	{
		$this->mustBeSignedIn();
		$this->load->model(array('Group_Invite'));		
		$redirect = $this->getRedirect();
		if ($this->Group_Invite->delete($key)) 
		{
			$this->redirect($redirect, 'Invitation successfully deleted.');	
		}
		else {
			$this->redirect($redirect, 'The invitation could not be deleted.', 'error');	
		}
	}
	

	/**
	 * View a group's inbox
	 *
	 * @todo Change that bad hand-off of user data
	 * @param string $groupname	
	 * @access public	
	 * @return
	 */
	public function inbox($groupname = null)
	{
		$this->mustBeSignedIn();
		if ($groupname) {
			$group = $this->Group->getByName($groupname);
			if (!$group){
				$this->redirect("/groups");
			}
			$this->data['page_title'] = ucwords($group['fullname']);
			if (!$this->Group->isMember($group['members'], $this->userData['id'])) 
			{
				$this->redirect('/group/'.$group['name']);
			}	
			$user = $this->data['User'];
			$this->data = $group; //necessary, but should be removed, this was accidently coded to overwriter user data
			$this->data['group'] = $group;
			$this->data['group_select'] = $this->Group->membersSelect($this->userData, $group);
			$this->data['page_title'] = $group['name'] . ' Inbox';
			$this->data['groupname'] = $group['name'];		
			$this->data['group']['is_owner'] = $this->Group->isOwner($this->userData['id'], $group['owner_id']);
			$this->data['group']['member_count'] = count($group['members']);			
			$this->data['messages'] = $this->Message->getMany($group['inbox']);
			$this->data['group']['im_a_member'] = $this->Group->isMember($group['members'], $this->userData['id']);
			$this->data['to'] = '!' . $group['name'];
			$this->data['User'] = $user;
			$this->load->view('groups/inbox', $this->data);
		} else {
			$this->redirect("/groups/","Sorry but we couldn't find what you were looking for","error");
		}
	}

	/**
	 * List all groups
	 *
	 * @access public
	 * @return	
	 */
	public function index()
	{
		$groups = $this->Group->getAll();
		$this->data['page_title']  = ucwords($this->config->item('group')."s");
       	$this->data['groups'] = Page::make('Group', $groups['all'], array('method'=>'getMany'));
		$this->loadGroupSidebar = false;
		$this->load->view('groups/index', $this->data);
	}

	/**
	 * Invite users
	 *
	 * @access public
	 * @param string $groupname
	 * @return 
	 */
	public function invites($groupname = null)
	{
		$this->mustBeSignedIn();
		if (!$groupname)
		{
			$this->redirect('/groups/');
		}
		$this->load->model(array('Group_Invite'));		
		$group =  $this->Group->getByName($groupname);
		if (empty($group)){
			$this->redirect('/groups');
		}
		$this->data['page_title'] = ucwords($group['fullname']);
		$this->data['group'] = $group;
		$this->data['page_title'] = 'Invite people to '.$this->data['group']['name'];
		if ((!$this->data['group']) || (!$this->Group->isOwner($this->userData['id'], $this->data['group']['owner_id'])))
		{
			$this->redirect('/groups');
		}
		$this->data['group']['is_owner'] = $this->Group->isOwner($this->userData['id'], null, $this->data['group']['id']);
		$this->data['group']['im_a_member'] = in_array($this->userData['id'], $this->data['group']['members']);	
		if ($this->postData) 
		{
			$this->Group_Invite->addMany($this->postData['invites'], $this->data['group']);
			foreach ($this->Group_Invite->successes as $invite) {
				$invitee = $this->User->getByEmail($invite['email']);
				if (!empty($invitee)) { 
					$invite = $invite + $invitee;
				}
				$this->mail->sendGroupInvite($invite, $this->userData, $this->data['group']['name'], $this->config->item('base_url') . 'groups/accept/' . $invite['key']);
			}
			$this->redirect($_SERVER['REQUEST_URI'], $this->Group_Invite->message);			
		}
		$this->data['invite_list'] = $this->Group_Invite->getMany($this->data['group']['invites']);
		$this->load->view('groups/invites', $this->data);
	}	

	/**
	 * View members
	 *	
	 * @access public
	 * @param string $groupname
	 * @param string $sidebar	
	 * @return 
	 */
	public function members($groupname = null, $sidebar = null)
	{
		$this->mustBeSignedIn();		
		$group = $this->Group->getByName($groupname);
		$this->data['page_title'] = ucwords($group['fullname']);
		if ($group) 
		{
			$this->data = $group;
			$this->data['group'] = $group;
			$this->data['User'] = $this->userData;
			$this->data['group']['is_owner'] = $this->Group->isOwner($this->userData['id'], null, $group['id']);
			$this->data['group']['im_a_member'] = in_array($this->userData['id'], $group['members']);			
			$this->data['page_title'] = $group['name'] . ' Members';
			$this->data['groupname'] = $group['name'];
			$this->data['owner'] = $group['owner_id'];			
        	$this->data['members'] = Page::make('Group', $group, array('method'=>'getMembers'));
			if (empty($sidebar))
			{
				$this->load->view('groups/members', $this->data);
			}
			else{
				return $this->data;
			}						
		} 
		else 
		{
			$this->redirect("/groups");
		}
	}

	/**
	 * Mentions
	 *
	 * @param string $groupname
	 * @access public
	 * @return 
	 */
	public function mentions($groupname = null)
	{
		$this->mustBeSignedIn();
		$group = $this->Group->getByName($groupname);
		$this->data['page_title'] = ucwords($group['fullname']);
		if ($group) 
		{
			$this->data['group'] = $group;
			$this->data['group']['is_owner'] = $this->Group->isOwner($this->userData['id'], null, $group['id']);
			$this->data['group']['im_a_member'] = in_array($this->userData['id'], $group['members']);	
			$this->data['page_title'] = 'Mentions';	
	        $this->data['messages'] = Page::make('Message', $group['mentions']);
			$this->load->view('groups/mentions', $this->data);
		} 
		else 
		{
			$this->redirect('/groups/',"We're sorry, but we didn't have what you were looking for.");
		}
	}

	/**
	 * Remove member
	 *
	 * @access public
	 * @param int $group_id
	 * @param int $user_id	
	 * @param boolean $blacklist Also blacklist the user?		
	 * @return 
	 */
	public function remove($group_id = null, $user_id = null, $blacklist = false)
	{
		$this->mustBeSignedIn();
		$group = $this->Group->get($group_id);	//get group
		$this->mustBeOwner($group);
		if (empty($group)) 	//if either returns no data show 404
		{
			$this->redirect("/groups");
		} 
		else 
		{
			if ($this->Group->removeMember($group, $user_id))	//remove member 
			{
				$this->User->removeGroup($user_id, $group_id);
				if ($blacklist) 
				{
					$this->Group->addToBlacklist($group, $user_id);
					$message = 'The member was removed and blacklisted. <a href="/groups/blacklist/' . $group['name'] . '">View blacklist.</a>';
				} 
				else 
				{
					$message = 'The member was removed.';
				}
				$this->redirect('/groups/members/' . $group['name'], $message);
			} 
			else 
			{
				$this->redirect('/groups/members/' . $group['name'], 'Could not remove member from '.$this->config->item('group').'.', 'error');
			}
		}
	}

	/**
	 * Show group's public profile
	 *
	 * @param string $groupname
	 * @access public
	 * @return null
	 */
	public function profile($groupname = null)
	{
		$user = $this->data['User'];
		$group = $this->Group->getByName($groupname);
		$this->data['page_title'] = ucwords($group['fullname']);
		if (!$group){
			$this->redirect('/groups/');
		}
		$this->data['page_title'] = ucfirst($this->config->item('group')).' Settings';
		$this->data = $group;
		$this->data['group'] = $group;
		$this->data['group']['is_owner'] = $this->Group->isOwner($this->userData['id'], null, $group['id']);
		$this->data['group']['im_a_member'] = in_array($this->userData['id'], $group['members']);	
		$this->data['User'] = $user;
		$this->setData($this->data);
		$this->load->view('groups/profile', $this->data); 
	}

	/**
	 * Update a user profile
	 *
	 * @param string $groupname 
	 * @access public
	 * @return 
	 */
	public function settings($groupname = null)
	{
		$this->mustBeSignedIn();
		$user = $this->data['User'];
		$group = $this->Group->getByName($groupname);
		$this->data['page_title'] = ucwords($group['fullname']);
		if (!$group){
			$this->redirect('/groups/');
		}
		$this->data['page_title'] = ucfirst($this->config->item('group')).' Settings';
		if ($this->Group->isOwner($this->userData['id'], $group['owner_id'])) 
		{
			$this->data = $group;
			$this->data['group'] = $group;
			$this->data['group']['is_owner'] = $this->Group->isOwner($this->userData['id'], null, $group['id']);
			$this->data['group']['im_a_member'] = in_array($this->userData['id'], $group['members']);	
			$this->data['User'] = $user;
			if ($this->postData) 
			{
				if ($this->Group->update($group, $this->postData, $this->userData['id'])) 
				{
					$this->redirect('/groups/settings/' . $this->postData['name'], 'The '.$this->config->item('group').' info was updated.');
				} 
				else 
				{
					$this->setErrors(array('Group'));
					$this->cookie->setFlash('There was an error updating your '.$this->config->item('group').'. See below for more details.', 'error');
				}
			}
			else 
			{
				$this->setData($this->data);
			}			
			$this->load->view('groups/settings', $this->data); 
		} 
		else 
		{
			$this->redirect('/groups/'.$group['name']);
		}
	}

	/**
	 * Subscribe to a group
	 *
	 * @access public
	 * @param int $group_id
	 * @todo Make $group_id into $groupname
	 * @todo Restore message to followers notifying that user has joined group	
	 * @return 
	 */
	public function subscribe($group_id = null)
	{
		$this->mustBeSignedIn();
		$group = $this->Group->get($group_id);
		if (!$group){
				$this->redirect("/groups");
		}
		$this->Group->addMember($group, $this->userData);
		$this->User->addGroup($this->userData, $group_id);
		//need to send array to send to followers
		//$message = 'I just became a member of !'. $group['name'];
		//$message_id = $this->Message->add(array('message'=>$message), $this->userData);
		//$this->User->sendToFollowers($message_id, $this->userData['followers']);
		$this->redirect('/group/' . $group['name']);
	}
	
	/**
	 * Unsubscribe from a group
	 *
	 * @access public
	 * @param int $group_id
	 * @todo Make $group_id into $groupname	
	 * @return 
	 */
	public function unsubscribe($group_id = null)
	{
		$this->mustBeSignedIn();
		$group = $this->Group->get($group_id);
		if (!$group){
				$this->redirect("/groups");
		}
		$this->Group->removeMember($group, $this->userData['id']);
		$this->User->removeGroup($this->userData['id'], $group_id);
		$this->redirect('/group/' . $group['name']);
	}	
	
	/**
	 * Remove user from blacklist
	 *
	 * @access public
	 * @param int $group_id
	 * @param int $user_id	
	 * @return 
	 */
	public function unblacklist($group_id = null, $user_id = null)
	{
		$this->mustBeSignedIn();
		$group = $this->Group->get($group_id);	//get group
		if (!$group){
				$this->redirect("/groups");
		}
		$this->mustBeOwner($group);
		if ($this->Group->removeFromBlacklist($group, $user_id))	//remove member 
		{
			$this->redirect('/groups/members/' . $group['name'], 'The user was removed from the blacklist.');
		} 
		else 
		{
			$this->redirect('/groups/members/' . $group['name'], 'Could not remove user from blacklist.', 'error');
		}
	}
	
	/**
	 * View a group
	 *
	 * @todo Change that bad hand-off of user data
	 * @access public
	 * @param string $name	
	 * @return
	 */
	public function view($groupname = null, $replyTo = null)
	{		
		$group = $this->Group->getByName($groupname);
		if (!$group){
				$this->redirect("/groups");
		}
		$this->data['page_title'] = ucwords($group['fullname']);
		$this->setReplyTo($replyTo);
		if (isset($this->data['User'])) 
		{
			$user = $this->data['User'];
		}
		else 
		{
			$user = array();
		}
		$this->data['group'] = $group;
		$this->data['page_title'] = $group['name'];
		$this->data['group']['groupname'] = $group['name'];
		$this->data['group']['is_owner'] = $this->Group->isOwner($this->userData['id'], $group['owner_id']);
		$this->data['group']['member_count'] = count($group['members']);			
		$this->data['group']['im_a_member'] = $this->Group->isMember($group['members'], $this->userData['id']);
		if ($this->data['group']['im_a_member']) 
		{
			if ($this->userData['threading']) 
			{
				$messages = $this->data['group']['messages_threaded'];
			}
			else 
			{
				$messages = $this->data['group']['messages'];
			}
			rsort($messages);
		} 
		else 
		{
			$messages = $this->data['group']['mentions'];				
		}
   	 	$this->data['messages'] = Page::make('Message', $messages);			
		if ($this->data['group']['member_count'] > 0) {
			$this->User->updateReadGroup($this->userData, $group);
			$this->data['redirect'] = $this->getRedirect(true,'/group/' . $group['name']);				
			$this->load->view('groups/view', $this->data);
		} 
		else {
			$this->redirect('/groups');
		}
	}
}
?>