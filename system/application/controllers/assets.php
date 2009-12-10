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
 * Asset loading functions to support themes
 * 
 * Because themes are stored in the filesystem outside of the webroot, we need 
 * to make unprocessed files available via http
 * 
 * @package 	Nomcat
 * @subpackage	nomcat-controllers
 * @category	controller
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Assets extends App_controller
{
	/**
	 * Returns the contents of the css file passed as a parameter
	 * 
	 * @param string $file
	 */
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
	
	/**
	 * Returns the contents of the javascript file passed as a parameter
	 * 
	 * @param string $file
	 */
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
	
	/**
	 * Return the contents of the image file passed as a parameter
	 * 
	 * @param string $file
	 */
	function img($file)
	{
		$file = APPPATH . 'views/themes/'. $this->config->item('theme') .'/img/' . $file;
		if (file_exists($file) && is_readable($file))
		{
		    $this->setHeader($file);
		    header("Content-size: " . filesize($file));
		    $handle = fopen($file, "r");
			$contents = fread($handle, filesize($file));
			fclose($handle);
			print $contents;
 			exit;
		}
	}
	
	/**
	 * Set the appropriate header for whichever file type is requested
	 * 
	 * @param string $file
	 */
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
			case 'pjpeg': $ctype = 'image/pjpeg';break;
			case 'png': $cytpe = 'image/png';break;
			case 'js': $ctype = 'text/javascript';break;
			case 'css': $ctype = 'text/css';break;
			case 'ico': $ctype = 'image/x-icon';
				//vnd.microsoft.icon'; 
				break;
			default: $ctype = 'text/plain';break;
		}
		
		header('Content-type: '.$ctype);
	}
}