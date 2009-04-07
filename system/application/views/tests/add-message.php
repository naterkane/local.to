<?php
	$name = $this->selenium->randomString(10);
	$password = $this->selenium->randomString(10);
	$message = $this->selenium->randomString(10);		
	$this->selenium->caseTitle('Add Message');
	$this->selenium->signOut();	
	$this->selenium->signUp($name, $password);
	$this->selenium->signIn($name, $password);
	$this->selenium->write('type', 'message', $message);
	$this->selenium->click('Update');
	$this->selenium->openPage('/');
	$this->selenium->write('verifyTextPresent', $message);	
	$this->selenium->openPage('/' . $name);	
	$this->selenium->write('verifyTextPresent', $message);
	$this->selenium->openPage('/users/home/');
	$this->selenium->write('verifyTextNotPresent', $message);
	$this->selenium->openPage('/admin/flush');
?>