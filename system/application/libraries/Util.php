<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Util 
 * 
 * Provides some basic reusable utility methods
 */
class Util
{

 		
	/**
	 * 
	 * @return 
	 * @param object $input
	 */
	public function base64_url_encode($input) {
    	return strtr(base64_encode($input), '+/=', '-_');
    }

	/**
	 * 
	 * @return 
	 * @param object $input
	 */
	public function base64_url_decode($input) {
	    return base64_decode(strtr($input, '-_', '+/='));
    }
	
	/**
	 * compares a string against the request URI
	 * @return 
	 * @param object $string
	 */
	public function isSection($string)
	{
		return stristr($_SERVER['REQUEST_URI'],$string);
	}
	
}
?>