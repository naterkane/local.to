<?php
/**
* Selenium class
*/
class Tests extends App_Controller
{

	/**
	 * Handles views for testing. 
	 *
	 * Shows 404 if app is not error reporting.
	 */	
	function index() 
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
        	show_404();
        }
	}
	
	/**
	 * Shortcut to forward to testing path
	 */
	function testme()
	{
		$this->redirect('/tests/TestRunner.html?test=/tests/testsuite-app');
	}
	
	
}
?>