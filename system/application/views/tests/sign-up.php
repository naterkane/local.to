<?php
	$name = $this->selenium->randomString(10);
	$password = $this->selenium->randomString(10);	
	$this->selenium->caseTitle('Sign Up');
	$this->selenium->signOut();	
	$this->selenium->openPage('/users/signup');		
	$this->selenium->click('Sign Up');
	$this->selenium->write('verifyTextPresent', 'There was an error signing up. Please see below for details.');
	$this->selenium->write('verifyTextPresent', 'A user name is required');
	$this->selenium->write('verifyTextPresent', 'A password is required');
	$this->selenium->signUp($name, $password);
	$this->selenium->signIn($name, $password);
	$this->selenium->openPage('/' . $name);
	$this->selenium->openPage('/admin/flush');	
?>