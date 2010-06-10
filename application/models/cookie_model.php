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
 * Cookie
 * 
 * Used to save cookies to db
 * 
 * @package 	Nomcat
 * @subpackage	Models
 * @category	Model
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Cookie_model extends App_Model
{
	/**
	 * @access protected
	 * @var array
	 */
	protected $fields = array(
			/**
			 * Random string
			 * @var string
			 */
			'id' => null, 
			/**
			 * Flash message
			 * @var string
			 */
			'flash_message' => null,
			/**
			 * Type of flash
			 * @var string
			 */
			'flash_type' => null,			
			/**
			 * User's IP address
			 * @var string
			 */
			'ip' => null, 
			/**
			 * Timestamp of time last accessed
			 * @var integer
			 */		
			'last_accessed' => null,
			/**
			 * Update key for account deletion, etc
			 * @var int
			 */ 
			'update_key' => null,
			/**
			 * User's id
			 * @var int
			 */ 
			'user' => null,
			/**
			 * User's browser and operating system information
			 * @var string
			 */ 
			'user_agent' => null 			
			);
			
	/**
	 * @access protected
	 * @var string
	 */
	protected $name = 'Session';
	
	/**
	 * Validates the presence of a cookie
	 * @return boolean
	 */
	public function validate()
	{
		$this->setAction();			
		$this->validates_presence_of('id', array('message'=>'A session id is required'));
	    return (count($this->validationErrors) == 0);		
	}
	
}
?>