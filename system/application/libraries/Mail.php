<?php
/**
* Mailer class
*/
class Mail
{
	
	public $carriers = array('@sms.3rivers.net'=>'3 River Wireless', '@cingularme.com'=>'7-11 Speakout', '@airtelkk.com'=>'Airtel (Karnataka, India)', '@msg.acsalaska.com'=>'Alaska Communications Systems', '@message.alltel.com'=>'Alltel Wireless', '@txt.att.net'=>'AT&T Wireless', '@txt.bell.ca'=>'Bell Mobility (Canada)', '@myboostmobile.com'=>'Boost Mobile', '@mobile.celloneusa.com'=>'Cellular One (Dobson)', '@cingularme.com'=>'Cingular (Postpaid)', '@cwemail.com'=>'Centennial Wireless', '@cingularme.com'=>'Cingular (GoPhone prepaid)', '@ideasclaro-ca.com'=>'Claro (Nicaragua)', '@comcel.com.co'=>'Comcel', '@sms.mycricket.com'=>'Cricket', '@sms.ctimovil.com.ar'=>'CTI', '@emtelworld.net'=>'Emtel (Mauritius)', '@fido.ca'=>'Fido (Canada)', '@msg.gci.net'=>'General Communications Inc.', '@msg.globalstarusa.com'=>'Globalstar', '@myhelio.com'=>'Helio', '@ivctext.com'=>'Illinois Valley Cellular', '.iws@iwspcs.net'=>'i wireless', '@sms.mymeteor.ie'=>'Meteor (Ireland)', '@sms.spicenepal.com'=>'Mero Mobile (Nepal)', '@mymetropcs.com'=>'MetroPCS', '@movimensaje.com.ar'=>'Movicom', '@sms.mobitel.lk'=>'Mobitel (Sri Lanka)', '@movistar.com.co'=>'Movistar (Colombia)', '@sms.co.za'=>'MTN (South Africa)', '@text.mtsmobility.com'=>'MTS (Canada)', '@nextel.net.ar'=>'Nextel (Argentina)', '@orange.pl'=>'Orange (Poland)', '@personal-net.com.ar'=>'Personal (Argentina)', '@text.plusgsm.pl'=>'Plus GSM (Poland)', '@txt.bell.ca'=>'President\'s Choice (Canada)', '@qwestmp.com'=>'Qwest', '@pcs.rogers.com'=>'Rogers (Canada)', '@sms.sasktel.com'=>'Sasktel (Canada)', '@mas.aw'=>'Setar Mobile email (Aruba)', '@txt.bell.ca'=>'Solo Mobile', '@messaging.sprintpcs.com'=>'Sprint (PCS)', '@page.nextel.com'=>'Sprint (Nextel)', '@tms.suncom.com'=>'Suncom', '@tmomail.net'=>'T-Mobile', '@sms.t-mobile.at'=>'T-Mobile (Austria)', '@msg.telus.com'=>'Telus Mobility (Canada)', '@sms.thumbcellular.com'=>'Thumb Cellular', '@sms.tigo.com.co'=>'Tigo (Formerly Ola)', '@utext.com'=>'Unicel', '@email.uscc.net'=>'US Cellular', '@vtext.com'=>'Verizon', '@vmobile.ca'=>'Virgin Mobile (Canada)', '@vmobl.com'=>'Virgin Mobile (USA)', '@sms.ycc.ru'=>'YCC', '@orange.net'=>'Orange (UK)', '@gocbw.com'=>'Cincinnati Bell Wireless', '@t-mobile-sms.de'=>'T-Mobile Germany', '@vodafone-sms.de'=>'Vodafone Germany', '@smsmail.eplus.de'=>'E-Plus');
	public $from_email;
	public $from_name;	
	
	public function __construct()
	{
		require_once(APPPATH . 'libraries/phpmailer/Phpmailer.php');
		$this->mail = new Phpmailer();
		$this->mail->IsSMTP();
		$ci = get_instance();
		$ci->config->item('username');
		$this->mail->Username = $ci->config->item('username');
		$this->mail->Password = $ci->config->item('password');
		$this->mail->AddReplyTo = 'Microblog';
		//$this->mail->Header = $this->mail->HeaderLine();
		$this->mail->Host = $ci->config->item('host');
		$this->mail->Port = $ci->config->item('port');
		$this->from_email = $ci->config->item('from_email');
		$this->from_name = $ci->config->item('from_name');		
		$this->mail->SMTPDebug = 0;
		$this->mail->SMTPAuth = true;
		$this->mail->SMTPSecure = "ssl";
		unset($ci);
	}
	
	/**
	 * Sends an email
	 * 
	 * @return 
	 * @param object $to
	 * @param object $from_email[optional]
	 * @param object $from_name[optional]
	 * @param object $subject[optional]
	 * @param object $message[optional]
	 */
	public function send($to, $from_email = null, $from_name = null, $subject = null, $message = null)
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
		$this->mail->SetFrom('Microblog', null);
		$this->mail->Subject = $subject;
		$this->mail->Body = $message;
		$this->mail->Send();
	}
	
	/**
	 * Sends an email
	 * 
	 * @return 
	 * @param object $to
	 * @param object $from_email
	 * @param object $message[optional]
	 */
	public function sms($to, $from, $message = null)
	{
		$this->mail->AddAddress($to);
		$this->mail->SetFrom($from);
		$this->mail->Body = $message;
		$this->mail->Send();
	}
	
}

?>