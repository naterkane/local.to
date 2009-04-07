<?php
	$name = $this->selenium->randomString(10);
	$password = $this->selenium->randomString(10);	
	$this->selenium->caseTitle('Sign Up');
	$this->selenium->openPage('/users/signout');
	$this->selenium->openPage('/users/signup');
	$this->selenium->write('type', 'username', $name);
	$this->selenium->write('type', 'password', $password);	
	$this->selenium->click('Sign Up');
	$this->selenium->write('type', 'username', $name);
	$this->selenium->write('type', 'password', $password);	
	$this->selenium->click('Sign In');
	$this->selenium->write('verifyTextPresent', 'Hello ' . $name);		
	$this->selenium->openPage('/admin/flush');	
?>