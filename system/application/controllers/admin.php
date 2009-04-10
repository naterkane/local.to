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
		echo "<pre>";
		print_r($this->User->mem->get('user:a'));
		echo "</pre>";
		exit;
		
		exit;
		$this->redirect('/home');
	}

	function stats()
	{
		$stats = $this->User->mem->getStats();
		fsockopen($this->host, $this->port, $errno, $errstr);
		$i = fwrite($this->_sock, $s);
		echo "<pre>";
		print_r($stats);
		echo "</pre>";
		exit;
	}
	
	function parseLink($link = null)
	{
		echo "<pre>";
		print_r($link);
		echo "</pre>";
		exit;
	}
	
	function test()
	{
		$r = 'whatever @testing whatever';
		$id = '123';
		$r = preg_replace('/(^|\s+)@([A-Za-z0-9]{1,64})/e', "'\\1@' . \$this->parseLink('\\2')", $r);
	    //$r = preg_replace('/^T ([A-Z0-9]{1,64}) /e', "'T '.common_at_link($id, '\\1').' '", $r);
	    //$r = preg_replace('/(^|\s+)@#([A-Za-z0-9]{1,64})/e', "'\\1@#'.common_at_hash_link($id, '\\2')", $r);
	    //$r = preg_replace('/(^|\s)!([A-Za-z0-9]{1,64})/e', "'\\1!'.common_group_link($id, '\\2')", $r);
		echo $r;
		exit;
	}

}

?>