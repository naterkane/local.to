<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* CodeIgniter Gravatar Helper
*
* @package      CodeIgniter
* @subpackage   Helpers
* @category     Helpers
*/
class Gravatar {
	function __construct()
	{
	}
	/**
	 * Generates an image tag of a gravatar from the Gravatar website using the specified params
	 * 
	 * @access public
	 * @return string
	 * @param string $email
	 * @param string $rating[optional]
	 * @param integer $size[optional]
	 * @param string $default[optional]
	 */
	
	function img( $email, $size = null,$rating = 'X',  $default = 'http://gravatar.com/avatar.php' ) {
	    // Hash the email address
	    
		
	    // Return the generated URL
	    return "<img width=\"{$size}\" height=\"{$size}\" src=\"{$this->url($email,$size,$rating,$default)}\" />";
	}
	
	/**
	 * Fetches the url of a gravatar from the Gravatar website using the specified params
	 * 
	 * @access public
	 * @return string
	 * @param string $email
	 * @param string $rating[optional]
	 * @param integer $size[optional]
	 * @param string $default[optional]
	 */
	function url( $email, $size = null,$rating = 'X',  $default = 'http://gravatar.com/avatar.php' ) {
	    // Hash the email address
	    $email = md5(strtolower($email));
		$size = ($size == null)?'80':$size;
	    // Return the generated URL
	    return "http://gravatar.com/avatar.php?gravatar_id="
	        .$email."&amp;rating="
	        .$rating."&amp;size="
	        .$size."&amp;default="
	        .urlencode($default);
	}
}
?>