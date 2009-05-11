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
		echo "<a href=\"/admin/flush\">Flush again</a> <a href=\"/admin/flush\">Go to Tests</a><br>";		
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
		$this->load->view('/admin/memcache');
	}

	/**
	 * dumps the TT database
	 * 
	 * path to database file needs to exist on the same server on which the App is running
	 * 
	 * @todo make the path to the .tch file dynamic or configurable
	 * @return 
	 */
	function showdata()
	{
        echo "<a href=\"/admin/flush\">Flush</a> <a href=\"/admin/showdata\">Reloads</a><br>";
 		echo "<pre>";
        print_r(shell_exec("/usr/local/bin/tchmgr list -nl -pv /data/mydb.tch"));
        //var_dump(exec("/usr/local/bin/tchmgr list -nl -pv /data/mydb.tch"));
        echo "</pre>";
        exit;

	}

	function test() 
	{
	}

}
?>