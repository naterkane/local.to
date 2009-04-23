<?php
/**
* Mailer class
*/
class Mail
{
	
	var $from_email;
	var $from_name;	
	
	function __construct()
	{
		require_once(APPPATH . 'libraries/phpmailer/Phpmailer.php');
		$this->mail = new Phpmailer();
		$this->mail->IsSMTP();
		$ci = get_instance();
		$ci->config->item('username');
		$this->mail->Username = $ci->config->item('username');
		$this->mail->Password = $ci->config->item('password');
		$this->mail->Host = $ci->config->item('host');
		$this->mail->Port = $ci->config->item('port');
		$this->from_email = $ci->config->item('from_email');
		$this->from_name = $ci->config->item('from_name');		
		$this->mail->SMTPDebug = 2;
		$this->mail->SMTPAuth = true;
		$this->mail->SMTPSecure = "ssl";
		unset($ci);
	}
	
	function send($to, $from_email = null, $from_name = null, $subject = null, $message = null)
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
		$this->mail->SetFrom($from_email, $from_name);
		$this->mail->Subject = $subject;
		$this->mail->Body = $message;
		$this->mail->Send();
	}
	
}

?>