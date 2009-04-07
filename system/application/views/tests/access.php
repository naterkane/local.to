<?php
	$name = $this->selenium->randomString(10);
	$password = $this->selenium->randomString(10);	
	$this->selenium->caseTitle('Access');
	//list of pages users must be logged in to view
	$this->selenium->signOut();	
	$this->selenium->mustBeLoggedIn('/home');	
	$this->selenium->openPage('/admin/flush');	
?>