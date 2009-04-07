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
		$this->redis->flush();
		?><script>confirm("DB Flushed");</script><?php
		$this->redirect('/home');
	}

}

?>