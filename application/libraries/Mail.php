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
 * Mail
 * 
 * @package 	Nomcat
 * @subpackage	Libraries
 * @category	Classes
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Mail
{
	/**
	 * @access private
	 * @var array
	 */
	private $settings = array();
	/**
	 * @var array
	 */
	public $carriers = array('@sms.3rivers.net'=>'3 River Wireless', '@cingularme.com'=>'7-11 Speakout', '@airtelkk.com'=>'Airtel (Karnataka, India)', '@msg.acsalaska.com'=>'Alaska Communications Systems', '@message.alltel.com'=>'Alltel Wireless', '@txt.att.net'=>'AT&T Wireless', '@txt.bell.ca'=>'Bell Mobility (Canada)', '@myboostmobile.com'=>'Boost Mobile', '@mobile.celloneusa.com'=>'Cellular One (Dobson)', '@cingularme.com'=>'Cingular (Postpaid)', '@cwemail.com'=>'Centennial Wireless', '@cingularme.com'=>'Cingular (GoPhone prepaid)', '@ideasclaro-ca.com'=>'Claro (Nicaragua)', '@comcel.com.co'=>'Comcel', '@sms.mycricket.com'=>'Cricket', '@sms.ctimovil.com.ar'=>'CTI', '@emtelworld.net'=>'Emtel (Mauritius)', '@fido.ca'=>'Fido (Canada)', '@msg.gci.net'=>'General Communications Inc.', '@msg.globalstarusa.com'=>'Globalstar', '@myhelio.com'=>'Helio', '@ivctext.com'=>'Illinois Valley Cellular', '.iws@iwspcs.net'=>'i wireless', '@sms.mymeteor.ie'=>'Meteor (Ireland)', '@sms.spicenepal.com'=>'Mero Mobile (Nepal)', '@mymetropcs.com'=>'MetroPCS', '@movimensaje.com.ar'=>'Movicom', '@sms.mobitel.lk'=>'Mobitel (Sri Lanka)', '@movistar.com.co'=>'Movistar (Colombia)', '@sms.co.za'=>'MTN (South Africa)', '@text.mtsmobility.com'=>'MTS (Canada)', '@nextel.net.ar'=>'Nextel (Argentina)', '@orange.pl'=>'Orange (Poland)', '@personal-net.com.ar'=>'Personal (Argentina)', '@text.plusgsm.pl'=>'Plus GSM (Poland)', '@txt.bell.ca'=>'President\'s Choice (Canada)', '@qwestmp.com'=>'Qwest', '@pcs.rogers.com'=>'Rogers (Canada)', '@sms.sasktel.com'=>'Sasktel (Canada)', '@mas.aw'=>'Setar Mobile email (Aruba)', '@txt.bell.ca'=>'Solo Mobile', '@messaging.sprintpcs.com'=>'Sprint (PCS)', '@page.nextel.com'=>'Sprint (Nextel)', '@tms.suncom.com'=>'Suncom', '@tmomail.net'=>'T-Mobile', '@sms.t-mobile.at'=>'T-Mobile (Austria)', '@msg.telus.com'=>'Telus Mobility (Canada)', '@sms.thumbcellular.com'=>'Thumb Cellular', '@sms.tigo.com.co'=>'Tigo (Formerly Ola)', '@utext.com'=>'Unicel', '@email.uscc.net'=>'US Cellular', '@vtext.com'=>'Verizon', '@vmobile.ca'=>'Virgin Mobile (Canada)', '@vmobl.com'=>'Virgin Mobile (USA)', '@sms.ycc.ru'=>'YCC', '@orange.net'=>'Orange (UK)', '@gocbw.com'=>'Cincinnati Bell Wireless', '@t-mobile-sms.de'=>'T-Mobile Germany', '@vodafone-sms.de'=>'Vodafone Germany', '@smsmail.eplus.de'=>'E-Plus');
	/**
	 * @var boolean $email_updates does the user receive email updates?
	 */
	public $email_updates = true;
	/**
	 * @var string
	 */
	public $from_email;
	/**
	 * @var string
	 */
	public $from_name;	
	
	/**
	 * Loads the current theme's email configuration, then loads and 
	 * instantiates the {@link phpmailer/Phpmailer.php} class
	 * 
	 * @see Phpmailer
	 */
	public function __construct()
	{
		$this->ci = get_instance();
		if (file_exists(APPPATH . 'views/themes/' . $this->ci->config->item('theme') . '/config/email.php')) 
		{
			$settings['base_url'] = $this->ci->config->item('base_url');
			$settings['noreply'] = $this->ci->config->item('from_email');//"noreply@".substr($settings['base_url'],7);
			require_once(APPPATH . 'views/themes/' . $this->ci->config->item('theme') . '/config/email.php');
			$this->settings = $settings;
		}
		require_once(APPPATH . 'libraries/phpmailer/Phpmailer.php');
		$this->mail = new Phpmailer();
		$this->mail->IsSMTP();
		$this->mail->Username = $this->ci->config->item('username');
		$this->mail->Password = $this->ci->config->item('password');
		$this->mail->Host = $this->ci->config->item('host');
		$this->mail->Port = $this->ci->config->item('port');
		$this->from_email = $this->ci->config->item('from_email');
		$this->from_name = $this->ci->config->item('from_name');		
		$this->mail->SMTPDebug = 0;
		$this->mail->SMTPAuth = true;
		$this->mail->SMTPSecure = "ssl";
		$this->mail->do_debug = 0;
	}
	
	/**
	 * Get the value of a setting
	 * 
	 * @param string $setting
	 * @return boolean|array
	 */
	public function getSetting($setting = null)
	{
		if (!$setting) 
		{
			return false;
		}
		$return = null;
		if (!empty($this->settings[$setting])) 
		{
			$return = $this->settings[$setting];
		}
		return $return;
	}
	
	/**
	 * Sends an email
	 * 
	 * @access private
	 * @param string $to
	 * @param string $subject[optional]
	 * @param string $message[optional]	
	 * @param string $from_email[optional]
	 * @param string $from_name[optional]
	 * @return boolean
	 */
	private function send($to, $subject = null, $message = null, $from_email = null, $from_name = null)
	{
		if (!$this->email_updates) 
		{
			return false;
		}
		if (!$from_email) 
		{
			$from_email = $this->from_email;
		}
		if (!$from_name) 
		{
			$from_name = $this->from_name;
		}
		$this->mail->AddAddress($to);
		$this->mail->SetFrom($this->from_email, null);
		$this->mail->Subject = $subject;
		$this->mail->Body = $message;
		try
		{
			ob_start();
			$this->mail->Send();
			$this->mail->ClearAllRecipients();
			ob_end_clean();	
		}
		catch(Exception $e)
		{			
			$this->ci->redirect('/', 'Caught exception: ',  $e->getMessage(), "\n");
		}		
		return true;
	}

	/**
	 * Sends an email notifying a user that their account has been deleted
	 * 
	 * @access public
	 * @param array $to
	 * @see send()
	 */
	public function sendDeletion($to = array())
	{
		$message = $this->getSetting('message_deletion');
		$message = str_replace('{to}', $to['realname'], $message);
		$message = str_replace('{username}', $to['username'], $message);
		$message .= $this->getSetting('signature');			
		$message .= "\n\nThis email was intended for " . $to['email'];
		$subject = $this->getSetting('subject_deletion');
		$this->send($to['email'], $subject, $message);
	}
	
	/**
	 * Send a user a DM via email email 
	 *
	 * @access public
	 * @param array $to
	 * @param array $from
	 * @param string $message	
	 */
	public function sendPrivateMessage($to = array(), $from = array(), $dm)
	{
		$this->email_updates = $to['email_updates'];
		$message = $this->getSetting('message_dm');		
		$message = str_replace('{link}', $this->ci->config->item('base_url')."inbox", $message);
		$message = str_replace('{to}', $to['realname'], $message);
		$message = str_replace('{username}', $to['username'], $message);
		$message = str_replace('{fromrealname}', $from['realname'], $message);
		$message = str_replace('{fromusername}', $from['username'], $message);	
		$message = str_replace('{message}', $dm, $message);		
		$message .= $this->getSetting('signature');		
		$message .= $this->getSetting('email_settings_link');		
		$message .= "\n\nThis email was intended for " . $to['email'];
		$subject = $this->getSetting('subject_dm');
		$subject = str_replace('{fromrealname}', $from['realname'], $subject);
		$subject = str_replace('{fromusername}', $from['username'], $subject);	
		$this->send($to['email'], $subject, $message);	
	}
	
	/**
	 * Send a user a DM sent to a group via email 
	 *
	 * @access public
	 * @param array $to
	 * @param array $from
	 * @param array $group
	 * @param array $message	
	 */
	public function sendGroupPrivateMessage($to = array(),$from = array(),$group = array(), $dm)
	{
		$this->email_updates = $to['email_updates'];
		$message = $this->getSetting('message_dm_group');		
		$message = str_replace('{link}', $this->ci->config->item('base_url')."inbox", $message);
		$message = str_replace('{to}', $to['realname'], $message);
		$message = str_replace('{username}', $to['username'], $message);
		$message = str_replace('{fromrealname}', $from['realname'], $message);
		$message = str_replace('{fromusername}', $from['username'], $message);
		$message = str_replace('{group}', $group['fullname'], $message);
		$message = str_replace('{message}', $dm['message'], $message);	
		$message .= $this->getSetting('signature');		
		$message .= $this->getSetting('email_settings_link');		
		$message .= "\n\nThis email was intended for " . $to['email'];
		$subject = $this->getSetting('subject_dm_group');
		$subject = str_replace('{fromrealname}', $from['realname'], $subject);
		$subject = str_replace('{fromusername}', $from['username'], $subject);	
		$subject = str_replace('{group}',htmlspecialchars_decode($group['name']), $subject);
		$this->send($to['email'], $subject, $message);	
	}	
	
	/**
	 * Sends an email notifying a user that a user has requested to follow their public stream
	 * 
	 * @access public
	 * @param array $to
	 * @param array $from
	 * @param string $link
	 * @see send()
	 */
	public function sendFriendRequest($to = array(), $from = array(), $link)
	{
		$this->email_updates = $to['email_updates'];
		$message = $this->getSetting('message_friend_request');		
		$message = str_replace('{link}', $link, $message);
		$message = str_replace('{to}', $to['realname'], $message);
		$message = str_replace('{username}', $to['username'], $message);
		$message = str_replace('{followerrealname}', $from['realname'], $message);
		$message = str_replace('{followerusername}', $from['username'], $message);			
		$message .= $this->getSetting('signature');		
		$message .= $this->getSetting('email_settings_link');		
		$message .= "\n\nThis email was intended for " . $to['email'];
		$subject = $this->getSetting('subject_friend_request');
		$subject = str_replace('{followerrealname}', $from['realname'], $subject);
		$subject = str_replace('{followerusername}', $from['username'], $subject);	
		$this->send($to['email'], $subject, $message);
	}

	/**
	 * Send an email notifying a user of a new follower
	 *
	 * @access public
	 * @param array $to
	 * @param array $following	
	 */
	public function sendFollowingConfirmation($to = array(), $following = array())
	{
		$this->email_updates = $to['email_updates'];
		$message = $this->getSetting('message_following');	
		$message = str_replace('{to}', $to['realname'], $message);
		$message = str_replace('{username}', $to['username'], $message);
		$message = str_replace('{followerrealname}', $following['realname'], $message);
		$message = str_replace('{followerusername}', $following['username'], $message);
		$message = str_replace('{link}', $this->ci->config->item('base_url') . $following['username'], $message);		
		$message .= $this->getSetting('signature');		
		$message .= $this->getSetting('email_settings_link');		
		$message .= "\n\nThis email was intended for " . $to['email'];
		$subject = $this->getSetting('subject_following');
		$subject = str_replace('{username}', $following['realname'], $subject);		
		$this->send($to['email'], $subject, $message);				
	}
	
	
	/**
	 * Sends an email notifying a user that they have been invited to be a member of a group
	 * 
	 * @access public
	 * @param array $to
	 * @param array $from
	 * @param string $groupname
	 * @param string $link
	 * @see send()
	 */
	public function sendGroupInvite($to = array(), $from = array(), $groupname, $link)
	{
		$message = $this->getSetting('message_group_invite');
		if (!empty($to['username'])):
			$message = str_replace("{to}", " ".$to['realname'] . " (".$to['username'].")",$message);
		else: 
			$message = str_replace("{to}","", $message);
		endif;
		$message = str_replace('{fromfullname}', $from['realname'], $message);
		$message = str_replace('{fromusername}', $from['username'], $message);
		$message = str_replace('{link}', $link, $message);
		$message = str_replace('{group}', $groupname, $message);
		$message .= $this->getSetting('signature');		
		$message .= $this->getSetting('email_settings_link');		
		$message .= "\n\nThis email was intended for " . $to['email'];	
		$subject = $this->getSetting('subject_group_invite');
		$subject = str_replace('{groupname}', $groupname, $subject);
		$this->send($to['email'], $subject, $message);
	}
	
	/**
	 * Sends an email notifying a non-user that they have been invited to the system and may have signup for a user account
	 * 
	 * @access public
	 * @param string $to
	 * @param string $link
	 * @see send()
	 */
	public function sendInvite($to, $link)
	{
		$message = $this->getSetting('message_invite');
		$message = str_replace('{link}', $link, $message);			
		$message .= $this->getSetting('signature');		
		$message .= "\n\nThis email was intended for " . $to;
		$subject = $this->getSetting('subject_invite');
		$this->send($to, $subject, $message);
	}
	
	/**
	 * Sends an email to a user who has forgotten their password
	 * 
	 * @access public 
	 * @param array $to
	 * @param string $link
	 * @see send()
	 */
	public function sendRecoverPassword($to = array(), $link)
	{
		$message = $this->getSetting('message_recover_password');
		$message = str_replace('{to}', $to['realname'], $message);
		$message = str_replace('{username}', $to['username'], $message);
		$message = str_replace('{link}', $link, $message);	
		$message .= $this->getSetting('signature');		
		$message .= "\n\nThis email was intended for " . $to['email'];
		$subject = $this->getSetting('subject_recover_password');
		$this->send($to['email'], $subject, $message);
	}

	/**
	 * Send an email notifying a user has been confirmed by a user
	 *
	 * @access public
	 * @param array $to
	 * @param array $following	
	 * @see send() 
	 */
	public function sendRequestConfirmation($to = array(), $user = array())
	{
		$this->email_updates = $to['email_updates'];
		$message = $this->getSetting('message_confirm');	
		$message = str_replace('{to}', $to['realname'], $message);
		$message = str_replace('{username}', $user['realname'], $message);
		$message = str_replace('{link}', $this->ci->config->item('base_url') . $user['username'], $message);		
		$message .= $this->getSetting('signature');		
		$message .= $this->getSetting('email_settings_link');		
		$message .= "\n\nThis email was intended for " . $to['email'];	
		$subject = $this->getSetting('subject_confirm');
		$subject = str_replace('{username}', $user['realname'], $subject);		
		$this->send($to['email'], $subject, $message);				
	}

	
	/**
	 * Sends an email to a user that contains a link with a new/reset password for their account
	 * 
	 * @access public 
	 * @param array $to
	 * @see send()
	 */
	public function sendResetPassword($to = array())
	{
		$message = $this->getSetting('message_reset_password');
		$message = str_replace('{to}', $to['realname'], $message);
		$message = str_replace('{username}', $to['username'], $message);
		$message .= $this->getSetting('signature');		
		$message .= "\n\nThis email was intended for " . $to['email'];
		$subject = $this->getSetting('subject_reset_password');
		$this->send($to['email'], $subject, $message);
	}
	
	/**
	 * Sends an email to a user notifying them that an account has been created with their email address
	 * and welcoming them to the system
	 * 
	 * @access public 
	 * @param array $to
	 * @see send()
	 */
	public function sendWelcome($to = array())
	{
		$message = $this->getSetting('message_welcome');
		$message = str_replace('{to}', $to['realname'], $message);
		$message = str_replace('{username}', $to['username'], $message);
		$message .= $this->getSetting('signature');	
		$message .= "\n\nThis email was intended for " . $to['email'];
		$subject = $this->getSetting('subject_welcome');
		$this->send($to['email'], $subject, $message);
	}
	
	/**
	 * Sends an sms message to a user via email
	 * 
	 * @access public
	 * @param string $to
	 * @param string $from
	 * @param string $message[optional]
	 * @param boolean $activated[optional]
	 * @param boolean $mustBeActivated[optional]
	 * @return boolean TRUE on success, FALSE if the user is not activated.
	 */
	public function sms($to, $from, $message = null, $activated = false, $mustBeActivated = true, $subject = null)
	{
		if ($mustBeActivated && !$activated) 
		{
			return false;
		}
		else
		{
			$this->mail->AddAddress($to);
			$this->mail->SetFrom($this->settings['noreply'], $from);
			$this->mail->Body = $message;
			$this->mail->Subject = $subject;
			$this->mail->Send();
			$this->mail->ClearAllRecipients();			
			return true;
		}
	}
	
}