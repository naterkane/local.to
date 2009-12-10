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
 * Selenium tests
 * 
 * @package 	Nomcat
 * @subpackage	nomcat-controllers
 * @category	controller
 * @author		NOM
 * @link		http://getnomcat.com/user_guide/
 */
class Tests extends App_Controller
{

	/**
	 * Handles views for testing. 
	 *
	 * Shows 404 if app is not error reporting.
	 * @access public
	 */	
	public function index() 
	{			
        if ($this->isTesting()) {
			if (strpos($_SERVER['REQUEST_URI'], 'testsuite') !== false) 
			{
				$this->layout = 'testsuite';
				$this->load->view('tests/' . $this->uri->segments[2]);
			}
			else 
			{
				$this->layout = 'testcase';			
				$this->load->view('tests/' . $this->uri->segments[2]);			
			}
        } else {
        	$this->show404();
        }
	}
	
	/**
	 * Shortcut to forward to testing path
	 * @access public
	 */
	public function testme()
	{
		$this->redirect('/tests/TestRunner.html?test=/tests/testsuite-app');
	}
	
	
}