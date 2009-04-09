<?php
if (!empty($messages)) 
{	
	foreach ($messages as $message) 
	{
		if (!empty($message)) 
		{
			$this->load->view('messages/viewpost', array('message'=>$message));
		}
	}
}
?>