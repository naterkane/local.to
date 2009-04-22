<?php
	$name = $this->selenium->randomString(10);
	$password = $this->selenium->randomString(10);	
	$email = $this->selenium->randomString(10) . '@' . $this->selenium->randomString(10) . '.com';	
	$name_new = $this->selenium->randomString(10);
	$email_new = $this->selenium->randomString(10) . '@' . $this->selenium->randomString(10) . '.com';	
	$bio = $this->selenium->randomString(10);
	$url = $this->selenium->randomString(10);
	$location = $this->selenium->randomString(10);	
	$real_name = $this->selenium->randomString(10);	
	$reserved = 'groups';	
	$too_long_name = $this->selenium->randomString(16);
	$too_long_bio = $this->selenium->randomString(161);	
	$error = 'There was an error updating your profile. See below for more details.';	
	$this->selenium->caseTitle('Change Profile');
	//create account and sign in
	$this->selenium->signOut();	
	$this->selenium->signUp($name, $password, $email);
	$this->selenium->signIn($name, $password);
	//go to settings	
	$this->selenium->openPage('/settings');
	$this->selenium->write('verifyValue', 'username', $name);
	$this->selenium->write('verifyValue', 'email', $email);
	//test empty record
	$this->selenium->write('type', 'username', '');
	$this->selenium->write('type', 'email', '');	
	$this->selenium->click('Update');
	$this->selenium->write('verifyTextPresent', $error);
	$this->selenium->write('verifyTextPresent', 'A username is required');
	$this->selenium->write('verifyTextPresent', 'A valid email is required');
	//try invalid email
	$this->selenium->write('type', 'email', $name);	
	$this->selenium->click('Update');	
	$this->selenium->write('verifyTextPresent', $error);
	$this->selenium->write('verifyTextPresent', 'A valid email is required');
	//try reserved username
	$this->selenium->write('type', 'username', $reserved);
	$this->selenium->click('Update');
	$this->selenium->write('verifyTextPresent', $error);	
	$this->selenium->write('verifyTextPresent', 'This is a reserved username');	
	//too long username
	$this->selenium->write('type', 'username', $too_long_name);
	$this->selenium->click('Update');
	$this->selenium->write('verifyTextPresent', $error);	
	$this->selenium->write('verifyTextPresent', 'A username must be between 1 and 15 characters long');	
	//bad characters in username
	$this->selenium->write('type', 'username', $name . '!');
	$this->selenium->click('Update');
	$this->selenium->write('verifyTextPresent', $error);
	$this->selenium->write('verifyTextPresent', 'A username may only be made up of numbers, letters, and underscores');	
	//too long bio
	$this->selenium->write('type', 'bio', $too_long_bio);
	$this->selenium->click('Update');
	$this->selenium->write('verifyTextPresent', $error);	
	$this->selenium->write('verifyTextPresent', 'A bio must be between 1 and 160 characters long');	
	//update record
	$this->selenium->write('type', 'username', $name_new);
	$this->selenium->write('type', 'email', $email_new);	
	$this->selenium->write('type', 'bio', $bio);		
	$this->selenium->write('type', 'realname', $real_name);
	$this->selenium->write('type', 'location', $location);	
	$this->selenium->write('type', 'url', $url);
	$this->selenium->click('Update');
	$this->selenium->write('verifyTextPresent', 'Your profile was updated.');
	$this->selenium->openPage('/settings');	
	$this->selenium->write('verifyValue', 'username', $name_new);
	$this->selenium->write('verifyValue', 'email', $email_new);	
	$this->selenium->write('verifyValue', 'bio', $bio);		
	$this->selenium->write('verifyValue', 'realname', $real_name);
	$this->selenium->write('verifyValue', 'location', $location);	
	$this->selenium->write('verifyValue', 'url', $url);		
	$this->selenium->openPage('/admin/flush');	
?>