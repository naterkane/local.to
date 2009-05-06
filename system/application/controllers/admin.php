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

	/**
	 * loads the memcache control panel
	 * @return 
	 * @todo rewrite views/admin/memcache.php and make it work with TT/TC
	 */
	function memcache()
	{
		$this->load->view('admin/memcache');
	}

	function test() 
	{
		$user = $this->User->mem->get('user:5');
	}

}
?>