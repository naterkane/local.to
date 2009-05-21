<?php
/**
* Asset loading functions to support themes
*/
class Assets extends App_controller
{
	
	function css($file)
	{
		$this->setHeader($file);
		$file = APPPATH . 'views/themes/'. $this->config->item('theme') .'/css/' . $file;
		if (file_exists($file)) 
		{ 
			include_once($file);
			exit;	
		}
	}
	function js($file)
	{
		$this->setHeader($file);
		$file = APPPATH . 'views/themes/'. $this->config->item('theme') .'/js/' . $file;
		if (file_exists($file)) 
		{ 
			include_once($file);
			exit;	
		}
	}
	
	private function setHeader($file)
	{
		$file = split('[.]',$file);
		$ext = $file[count($file)-1];
		$ctype = '';// content type
		switch ($ext) {
			case 'js': $ctype = 'text/javascript';break;
			case 'css': $ctype = 'text/css';break;
			default: $ctype = 'text/plain';break;
		}
		
		header('Content-type: '.$ctype);
	}
}
?>