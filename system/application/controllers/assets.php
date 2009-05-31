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
	
	function img($file)
	{
		$file = APPPATH . 'views/themes/'. $this->config->item('theme') .'/img/' . $file;
		if (file_exists($file))
		{
		    $this->setHeader($file);
		    header("Content-size: " . filesize($file));
		    readfile($file);
			exit;
		}
	}
	
	private function setHeader($file)
	{
		$file = split('[.]',$file);
		$ext = $file[count($file)-1];
		$ctype = '';// content type
		switch ($ext) {
			case 'gif': $ctype = 'image/gif';break;
			case 'jpeg':
			case 'jpg':
			case 'jpe': $ctype = 'image/jpeg';break;
			case 'png': $cytpe = 'image/png';break;
			case 'js': $ctype = 'text/javascript';break;
			case 'css': $ctype = 'text/css';break;
			default: $ctype = 'text/plain';break;
		}
		
		header('Content-type: '.$ctype);
	}
}
?>