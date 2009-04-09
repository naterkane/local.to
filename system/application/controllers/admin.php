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
		$this->User->mem->flush();
		$this->redirect('/home');
	}

	function all()
	{
		$stats = $this->User->mem->getStats();
		echo "<pre>";
		print_r($stats);
		echo "</pre>";
		exit;
		
	}

}

?>