<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
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
 * Util
 * 
 * Provides some basic reusable utility methods
 * 
 * @package 	Nomcat
 * @subpackage	Libraries
 * @category	Exception
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Util
{

	/**
	 * Encode a string to base 64
	 * 
	 * @param string $input
	 * @return string base64 encoded string
	 */
	public function base64_url_encode($input) {
    		return strtr(base64_encode($input), '+/=', '-_');
    }

	/**
	 * Decode a string from base 64
	 * 
	 * @param string $input
	 * @return string base64 decoded string
	 */
	public function base64_url_decode($input) {
	    return base64_decode(strtr($input, '-_', '+/='));
    }
	
	/**
	 * compares a string against the request URI
	 * 
	 * @param string $string
	 * @return boolean
	 */
	public function isSection($string)
	{
		return stristr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],$string);
	}
	
}
?>