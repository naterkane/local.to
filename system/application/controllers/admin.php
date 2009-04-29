<?php
/**
* Db admin functions
*/
class Admin extends App_controller
{
	
	/**
	 * Flushes the database
	 * @return 
	 * @todo make private method and require authentication
	 */
	function flush() 
	{
		$this->User->tt->vanish();
		$this->redirect('/admin/stats');
	}
	
	function stats()
	{
		$all = $this->User->tt->fwmkeys('', 1000);	
		$stats = $this->User->tt->stat();
		echo "<a href=\"/admin/flush\">Flush</a><br>";		
		echo "<pre>";
		print_r($all);
		print_r($stats);
		echo "</pre>";
		exit;
	}

	function test() 
	{
		echo "<pre>";
		print_r($_GET);
		echo "</pre>";
		exit;
		
		
	}

}
?>