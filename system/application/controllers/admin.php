<?php
/**
* Db admin functions
*/
class Admin extends App_controller
{

	function flush() 
	{
		$this->redis->flush();
		echo "DB flushed.";
		exit;
	}

}

?>