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
 * App_URI
 * 
 * Extension of CI_URI
 * 
 * @package 	Nomcat
 * @subpackage	Libraries
 * @category	URI
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class App_URI extends CI_URI
{
	/**
	 * Container to store $_GET variables
	 * @var array
	 */
	public $params = array();
	
	/**
	 * Stores $_GET variables as $params, then wipes $_GET variables and calls parent constructor
	 */
	public function __construct()
	{
		$this->params = $_GET;
		$_GET = null;
		parent::CI_URI();
	}
	
}
?>