<?php
	$name = $this->selenium->randomString(10);
	$password = $this->selenium->randomString(10);	
	$this->selenium->caseTitle('Access');
	//list of pages users must be logged in to view
	$this->selenium->signOut();	
	$this->selenium->mustBeLoggedIn('/home');
	$this->selenium->mustBeLoggedIn('/groups/subscribe/xyz');
	$this->selenium->mustBeLoggedIn('/groups/add');	
	$this->selenium->openPage('/admin/flush');	
?>