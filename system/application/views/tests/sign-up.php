<?php
	$name = $this->selenium->randomString(10);
	$password = $this->selenium->randomString(10);
	$email = $this->selenium->randomString(10) . '@' . $this->selenium->randomString(10) . '.com';
	$too_short_name = $this->selenium->randomString(5);
	$too_long_name = $this->selenium->randomString(26);
	$error = 'There was an error signing up. Please see below for details.';
	$this->selenium->caseTitle('Sign Up');
	$this->selenium->signOut();	
	$this->selenium->openPage('/signup');		
	//test empty record
	$this->selenium->click('Sign Up');
	$this->selenium->write('verifyTextPresent', $error);
	$this->selenium->write('verifyTextPresent', 'A username is required');
	$this->selenium->write('verifyTextPresent', 'A password is required');
	$this->selenium->write('verifyTextPresent', 'A valid email is required');
	//try invalid email
	$this->selenium->write('type', 'email', $name);	
	$this->selenium->click('Sign Up');	
	$this->selenium->write('verifyTextPresent', $error);		
	$this->selenium->write('verifyTextPresent', 'A valid email is required');	
	//test password same as username
	$this->selenium->write('type', 'username', $name);
	$this->selenium->write('type', 'password', $name);
	$this->selenium->write('type', 'passwordconfirm', $name);	
	$this->selenium->click('Sign Up');
	$this->selenium->write('verifyTextPresent', $error);	
	$this->selenium->write('verifyTextPresent', 'Your password cannot be the same as your username');
	//too short username
	$this->selenium->write('type', 'username', $too_short_name);
	$this->selenium->click('Sign Up');
	$this->selenium->write('verifyTextPresent', $error);	
	$this->selenium->write('verifyTextPresent', 'A username must be between 6 and 25 characters long');
	//too long username
	$this->selenium->write('type', 'username', $too_long_name);
	$this->selenium->click('Sign Up');
	$this->selenium->write('verifyTextPresent', $error);	
	$this->selenium->write('verifyTextPresent', 'A username must be between 6 and 25 characters long');	
	//too short password
	$this->selenium->write('type', 'username', '');
	$this->selenium->write('type', 'password', $too_short_name);
	$this->selenium->write('type', 'passwordconfirm', $too_short_name);	
	$this->selenium->click('Sign Up');
	$this->selenium->write('verifyTextPresent', $error);	
	$this->selenium->write('verifyTextPresent', 'A password must be between 6 and 25 characters long');
	//too long password
	$this->selenium->write('type', 'username', '');
	$this->selenium->write('type', 'password', $too_long_name);
	$this->selenium->write('type', 'passwordconfirm', $too_long_name);	
	$this->selenium->click('Sign Up');
	$this->selenium->write('verifyTextPresent', $error);	
	$this->selenium->write('verifyTextPresent', 'A password must be between 6 and 25 characters long');
	//bad characters in username
	$this->selenium->write('type', 'username', $name . '!');
	$this->selenium->write('type', 'password', $password);
	$this->selenium->write('type', 'passwordconfirm', $password);	
	$this->selenium->click('Sign Up');
	$this->selenium->write('verifyTextPresent', $error);
	$this->selenium->write('verifyTextPresent', 'A username may only be made up of numbers and letters');	
	//bad characters in password
	$this->selenium->write('type', 'username', $name);
	$this->selenium->write('type', 'password', $password . '!');
	$this->selenium->write('type', 'passwordconfirm', $password . '!');	
	$this->selenium->click('Sign Up');
	$this->selenium->write('verifyTextPresent', $error);
	$this->selenium->write('verifyTextPresent', 'A password may only be made up of numbers and letters');
	//sign up
	$this->selenium->signUp($name, $password, $email);
	$this->selenium->signIn($name, $password);
	$this->selenium->openPage('/' . $name);
	$this->selenium->openPage('/signout');	
	//try and create an account with the same username
	$this->selenium->openPage('/signup');		
	$this->selenium->write('type', 'username', $name);
	$this->selenium->click('Sign Up');
	$this->selenium->openPage('/admin/flush');	
?>