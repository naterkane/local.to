<?php
	$name = $this->selenium->randomString(10);
	$password = $this->selenium->randomString(10);
	$message = $this->selenium->randomString(10);
	$message2 = $this->selenium->randomString(10);	
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
	$this->selenium->openPage('/public_timeline');	
	$this->selenium->write('verifyTextNotPresent', $message_long);		
	//post a message
	$this->selenium->write('type', 'message', $message);
	$this->selenium->click('Update');
	$this->selenium->openPage('/public_timeline');	
	$this->selenium->write('verifyTextNotPresent', $message_long);
	$this->selenium->openPage('/' . $name);	
	$this->selenium->write('verifyTextPresent', $message);
	$this->selenium->openPage('/home');
	$this->selenium->write('verifyTextPresent', $message);
	//post second message to timeline by unlocking
	$this->selenium->openPage('/settings');
	$this->selenium->write('assertChecked', 'locked');		
	$this->selenium->write('click', 'locked');	
	$this->selenium->click('Update');
	$this->selenium->write('assertNotChecked', 'locked');	
	$this->selenium->write('verifyTextPresent', 'Your profile was updated.');
	$this->selenium->openPage('/home');	
	$this->selenium->write('type', 'message', $message2);
	$this->selenium->click('Update');
	$this->selenium->openPage('/public_timeline');	
	$this->selenium->write('verifyTextPresent', $message2);
	$this->selenium->openPage('/' . $name);	
	$this->selenium->write('verifyTextPresent', $message2);
	$this->selenium->openPage('/home');
	$this->selenium->write('verifyTextPresent', $message2);	
	$this->selenium->openPage('/admin/flush');
?>