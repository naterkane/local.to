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
		$old = $this->User->timezones;
		$timeZones = timezone_identifiers_list();		
		$return = array();
		foreach ($old as $city => $zone) {
			$match = array_search($city, $timeZones);
			foreach ($timeZones as $key => $value) {
				$pos = strpos($value, $city);
				if ($pos !== false) {
					echo "'" . $value . "'=>'" . $zone . "',<br>";
				}
			}
		}
		exit;
		
		
		
	}

}
?>