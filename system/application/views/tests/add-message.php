<?php
	$name = $this->selenium->randomString(10);
	$password = $this->selenium->randomString(10);
	$message = $this->selenium->randomString(10);
	$message_long = $this->selenium->randomString(141);	
	$email = $this->selenium->randomString(10) . '@' . $this->selenium->randomString(10) . '.com';		
	$this->selenium->caseTitle('Add Message');
	$this->selenium->signOut();	
	$this->selenium->signUp($name, $password, $email);
	$this->selenium->signIn($name, $password);
	//try to submit a blank record
	$this->selenium->click('Update');
	$this->selenium->write('verifyTextNotPresent', 'ago');		
	$this->selenium->openPage('/' . $name);		
	$this->selenium->write('verifyTextNotPresent', 'ago');		
	//try to submit a 141 character message
	$this->selenium->openPage('/home');		
	$this->selenium->write('type', 'message', $message_long);	
	$this->selenium->click('Update');
	$this->selenium->write('verifyTextNotPresent', $message_long);	
	$this->selenium->openPage('/' . $name);			
	$this->selenium->write('verifyTextNotPresent', $message_long);	
	$this->selenium->openPage('/');	
	$this->selenium->write('verifyTextNotPresent', $message_long);		
	//post a message
	$this->selenium->write('type', 'message', $message);
	$this->selenium->click('Update');
	$this->selenium->openPage('/');
	$this->selenium->write('verifyTextPresent', $message);	
	$this->selenium->openPage('/' . $name);	
	$this->selenium->write('verifyTextPresent', $message);
	$this->selenium->openPage('/home');
	$this->selenium->write('verifyTextPresent', $message);
	$this->selenium->openPage('/admin/flush');
?>