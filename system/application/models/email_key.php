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
 * Email key
 * 
 * Used to save cookies to db
 * 
 * @package 	Nomcat
 * @subpackage	Models
 * @category	Model
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Email_key extends App_Model
{
	/**
	 * @access protected
	 * @var array
	 */
	protected $fields = array(
			/**
			 * Timestamp of time created
			 * @var integer
			 */
			'created' => null, 		
			/**
			 * A random, hashed string
			 * @var string
			 */
			'id' => null, 
			/**
			 * Unhashed version of id
			 * @var string
			 */
			'id_unhashed' => null, 
			/**
			 * User's id
			 * @var string
			 */
			'user_id' => null 
			);
	/**
	 * @access protected
	 * @var string
	 */
	protected $name = 'Email_key';

}
?>