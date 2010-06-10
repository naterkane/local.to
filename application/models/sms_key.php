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
 * SMS Key
 * 
 * @package 	Nomcat
 * @subpackage	Models
 * @category	Model
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Sms_key extends App_Model
{
	/**
	 * @access protected
	 * @var array
	 */
	protected $fields = array(
			/**
			 * Timestamp of time key is created
			 * @var integer
			 */
			'created' => null, 
			/**
			 * Random string
			 * @var string
			 */	
			'key' => null,
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
	protected $name = 'Sms_key';
	
	/**
	 * @var string
	 */
	public $key;	

	/**
	 * Delete a user's sms key
	 * 
	 * @access public
	 * @param int $user_id
	 * @return boolean
	 */
	public function delete($user_id)
	{
		return parent::delete($user_id, array('prefixValue'=>'user_id'));
	}

	/**
	 * Find a user's sms key
	 * 
	 * @access public
	 * @param int $user_id
	 * @return array|null
	 */
	public function find($user_id)
	{
		return parent::find($user_id, array('prefixValue'=>'user_id'));
	}

	/**
	 * Save a user's sms key
	 * 
	 * @access public 
	 * @param array $data[optional]
	 * @return boolean
	 */
	public function save($data = array())
	{
		return parent::save($data, array('prefixValue'=>'user_id'));
	}

}
?>