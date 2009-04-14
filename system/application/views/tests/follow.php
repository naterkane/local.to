<?php
	$name = $this->selenium->randomString(10);
	$password = $this->selenium->randomString(10);	
	$email = $this->selenium->randomString(10) . '@' . $this->selenium->randomString(10) . '.com';	
	$name2 = $this->selenium->randomString(10);
	$password2 = $this->selenium->randomString(10);	
	$email2 = $this->selenium->randomString(10) . '@' . $this->selenium->randomString(10) . '.com';	
	$message = $this->selenium->randomString(10);
	$message2 = $this->selenium->randomString(10);				
	$this->selenium->caseTitle('Follow');
	//create first account and sign out
	$this->selenium->signOut();	
	$this->selenium->signUp($name, $password, $email);
	$this->selenium->signIn($name, $password);
	$this->selenium->signOut();	
	//create second account	
	$this->selenium->signOut();	
	$this->selenium->signUp($name2, $password2, $email2);
	$this->selenium->signIn($name2, $password2);
	//follow first account
	$this->selenium->openPage('/' . $name);		
	$this->selenium->write('clickAndWait', 'follow');
	$this->selenium->write('verifyTextPresent', 'Following');
	$this->selenium->signOut();		
	//post as first account
	$this->selenium->signIn($name, $password);
	$this->selenium->write('type', 'message', $message);
	$this->selenium->click('Update');
	$this->selenium->openPage('/');
	$this->selenium->write('verifyTextPresent', $message);
	$this->selenium->signOut();		
	//make sure second account sees post			
	$this->selenium->signIn($name2, $password2);
	$this->selenium->write('verifyTextPresent', $message);
	$this->selenium->openPage('/admin/flush');	
?>