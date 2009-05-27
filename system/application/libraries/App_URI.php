<?php
class App_URI extends CI_URI
{
	
	public $params = array();
	
	public function __construct()
	{
		$this->params = $_GET;
		$_GET = null;
		parent::CI_URI();
	}
	
}
?>