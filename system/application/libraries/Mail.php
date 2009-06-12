<?php
/**
* Mailer class
*/
class Mail
{
	
	private $settings = array();
	public $carriers = array('@sms.3rivers.net'=>'3 River Wireless', '@cingularme.com'=>'7-11 Speakout', '@airtelkk.com'=>'Airtel (Karnataka, India)', '@msg.acsalaska.com'=>'Alaska Communications Systems', '@message.alltel.com'=>'Alltel Wireless', '@txt.att.net'=>'AT&T Wireless', '@txt.bell.ca'=>'Bell Mobility (Canada)', '@myboostmobile.com'=>'Boost Mobile', '@mobile.celloneusa.com'=>'Cellular One (Dobson)', '@cingularme.com'=>'Cingular (Postpaid)', '@cwemail.com'=>'Centennial Wireless', '@cingularme.com'=>'Cingular (GoPhone prepaid)', '@ideasclaro-ca.com'=>'Claro (Nicaragua)', '@comcel.com.co'=>'Comcel', '@sms.mycricket.com'=>'Cricket', '@sms.ctimovil.com.ar'=>'CTI', '@emtelworld.net'=>'Emtel (Mauritius)', '@fido.ca'=>'Fido (Canada)', '@msg.gci.net'=>'General Communications Inc.', '@msg.globalstarusa.com'=>'Globalstar', '@myhelio.com'=>'Helio', '@ivctext.com'=>'Illinois Valley Cellular', '.iws@iwspcs.net'=>'i wireless', '@sms.mymeteor.ie'=>'Meteor (Ireland)', '@sms.spicenepal.com'=>'Mero Mobile (Nepal)', '@mymetropcs.com'=>'MetroPCS', '@movimensaje.com.ar'=>'Movicom', '@sms.mobitel.lk'=>'Mobitel (Sri Lanka)', '@movistar.com.co'=>'Movistar (Colombia)', '@sms.co.za'=>'MTN (South Africa)', '@text.mtsmobility.com'=>'MTS (Canada)', '@nextel.net.ar'=>'Nextel (Argentina)', '@orange.pl'=>'Orange (Poland)', '@personal-net.com.ar'=>'Personal (Argentina)', '@text.plusgsm.pl'=>'Plus GSM (Poland)', '@txt.bell.ca'=>'President\'s Choice (Canada)', '@qwestmp.com'=>'Qwest', '@pcs.rogers.com'=>'Rogers (Canada)', '@sms.sasktel.com'=>'Sasktel (Canada)', '@mas.aw'=>'Setar Mobile email (Aruba)', '@txt.bell.ca'=>'Solo Mobile', '@messaging.sprintpcs.com'=>'Sprint (PCS)', '@page.nextel.com'=>'Sprint (Nextel)', '@tms.suncom.com'=>'Suncom', '@tmomail.net'=>'T-Mobile', '@sms.t-mobile.at'=>'T-Mobile (Austria)', '@msg.telus.com'=>'Telus Mobility (Canada)', '@sms.thumbcellular.com'=>'Thumb Cellular', '@sms.tigo.com.co'=>'Tigo (Formerly Ola)', '@utext.com'=>'Unicel', '@email.uscc.net'=>'US Cellular', '@vtext.com'=>'Verizon', '@vmobile.ca'=>'Virgin Mobile (Canada)', '@vmobl.com'=>'Virgin Mobile (USA)', '@sms.ycc.ru'=>'YCC', '@orange.net'=>'Orange (UK)', '@gocbw.com'=>'Cincinnati Bell Wireless', '@t-mobile-sms.de'=>'T-Mobile Germany', '@vodafone-sms.de'=>'Vodafone Germany', '@smsmail.eplus.de'=>'E-Plus');
	public $from_email;
	public $from_name;	
	
	public function __construct()
	{
		$this->ci = get_instance();
		if (file_exists(APPPATH . 'views/themes/' . $this->ci->config->item('theme') . '/config/email.php')) 
		{
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
	
	public function getSetting($setting = null)
	{
		if (!$setting) 
		{
			return;
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
	 * @return 
	 * @param object $to
	 * @param object $subject[optional]
	 * @param object $message[optional]	
	 * @param object $from_email[optional]
	 * @param object $from_name[optional]
	 */
	private function send($to, $subject = null, $message = null, $from_email = null, $from_name = null)
	{
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
			ob_end_clean();	
		}
		catch(Exception $e)
		{
			$this->ci->redirect('/', 'Caught exception: ',  $e->getMessage(), "\n");
		}		
	}

	public function sendDeletion($to)
	{
		$message = $this->getSetting('message_deletion');
		$message .= $this->getSetting('signature');			
		$subject = $this->getSetting('subject_deletion');
		$this->send($to, $subject, $message);
	}
	
	public function sendFriendRequest($to, $username, $link)
	{
		$message = $this->getSetting('message_friend_request');
		$message = str_replace('{link}', $message, $link);		
		$message .= $this->getSetting('signature');			
		$subject = $this->getSetting('subject_friend_request');
		$subject = str_replace('{username}', $subject, $username);
		$this->send($to, $subject, $message);
	}
	
	public function sendGroupInvite($to, $groupname, $link)
	{
		$message = $this->getSetting('message_group_invite');
		$message = str_replace('{link}', $message, $link);		
		$message .= $this->getSetting('signature');			
		$subject = $this->getSetting('subject_group_invite');
		$subject = str_replace('{groupname}', $subject, $groupname);
		$this->send($to, $subject, $message);
	}
	
	public function sendInvite($to, $link)
	{
		$message = $this->getSetting('message_invite');
		$message = str_replace('{link}', $message, $link);			
		$message .= $this->getSetting('signature');		
		$subject = $this->getSetting('subject_invite');
		$this->send($to, $subject, $message);
	}
	
	public function sendRecoverPassword($to, $link)
	{
		$message = $this->getSetting('message_recover_password');
		$message = str_replace('{link}', $message, $link);	
		$message .= $this->getSetting('signature');		
		$subject = $this->getSetting('subject_recover_password');
		$this->send($to, $subject, $message);
	}
	
	public function sendResetPassword($to)
	{
		$message = $this->getSetting('message_reset_password');
		$message .= $this->getSetting('signature');		
		$subject = $this->getSetting('subject_reset_password');
		$this->send($to, $subject, $message);
	}
	
	public function sendWelcome($to)
	{
		$message = $this->getSetting('message_welcome');
		$message .= $this->getSetting('signature');			
		$subject = $this->getSetting('subject_welcome');
		$this->send($to, $subject, $message);
	}
	
	/**
	 * Sends an email
	 * 
	 * @return 
	 * @param object $to
	 * @param object $from_email
	 * @param object $message[optional]
	 */
	public function sms($to, $from, $message = null, $activated = false, $mustBeAccivated = true)
	{
		if ($mustBeActivated && !$activated) 
		{
			return;
		}
		else
		{
			$this->mail->AddAddress($to);
			$this->mail->SetFrom($from);
			$this->mail->Body = $message;
			$this->mail->Send();
		}
	}
	
}

?>