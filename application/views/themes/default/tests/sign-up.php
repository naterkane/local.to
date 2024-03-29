<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);
	$email = "nomcat+".$selenium->randomString(10) . '@wearenom.com';
	$too_long_name = $selenium->randomString(16);
	$too_long_real_name = $selenium->randomString(51);	
	$too_short_password = $selenium->randomString(5);	
	$too_long_password = $selenium->randomString(26);	
	$count = $this->testingData['count'];	
	$error = 'There was an error signing up. Please see below for details.';
	$reserved = 'groups';
	$selenium->caseTitle('Sign Up');
	$selenium->signOut();		
	//create an invite and store
	$selenium->openPage('/admin/request_invite');
	$selenium->write('type', 'email', $email);
	$selenium->write('type', 'emailconfirm', $email);
	$selenium->write('clickAndWait', 'signMeUp');	
	$selenium->write('storeValue', 'key', 'invite_key');	
	//test no keys or bad keys
	$selenium->write('openAndWait', '/signup');	
	$selenium->write('verifyTextPresent', $selenium->missingText);
	$selenium->write('openAndWait', '/signup/123');	
	$selenium->write('verifyTextPresent', $selenium->missingText);	
	$selenium->write('openAndWait', '/signup/123/123');	
	$selenium->write('verifyTextPresent', $selenium->missingText);	
	//test empty record
	$selenium->openPage('/signup/${invite_key}');
	$selenium->write('type', 'email', '');	
	$selenium->click('Sign Up');
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A username is required');
	$selenium->write('verifyTextPresent', 'A password is required');
	$selenium->write('verifyTextPresent', 'Full name is required');	
	$selenium->write('verifyTextPresent', 'A valid email is required');
	$selenium->write('verifyValue', 'testing_count', $count);	
	//try invalid email
	$selenium->write('type', 'email', $name);	
	$selenium->click('Sign Up');	
	$selenium->write('verifyTextPresent', $error);		
	$selenium->write('verifyTextPresent', 'A valid email is required');	
	$selenium->write('verifyValue', 'testing_count', $count + 1);	
	//test password same as username
	$selenium->write('type', 'username', $name);
	$selenium->write('type', 'password', $name);
	$selenium->write('type', 'passwordconfirm', $name);	
	$selenium->click('Sign Up');
	$selenium->write('verifyTextPresent', $error);	
	$selenium->write('verifyTextPresent', 'Your password cannot be the same as your username');
	$selenium->write('verifyValue', 'testing_count', $count + 1);	
	//try reserved username
	$selenium->write('type', 'username', $reserved);
	$selenium->click('Sign Up');
	$selenium->write('verifyTextPresent', $error);	
	$selenium->write('verifyTextPresent', 'This is a reserved username');
	$selenium->write('verifyValue', 'testing_count', $count + 1);	
	//too long username
	$selenium->write('type', 'username', $too_long_name);
	$selenium->click('Sign Up');
	$selenium->write('verifyTextPresent', $error);	
	$selenium->write('verifyTextPresent', 'A username must be between 1 and 15 characters long');	
	$selenium->write('verifyValue', 'testing_count', $count + 1);
	//too long real name
	$selenium->write('type', 'realname', $too_long_real_name);
	$selenium->click('Sign Up');
	$selenium->write('verifyTextPresent', $error);	
	$selenium->write('verifyTextPresent', 'Full name must be between 1 and 50 characters long');	
	$selenium->write('verifyValue', 'testing_count', $count + 1);		
	//too short password
	$selenium->write('type', 'username', '');
	$selenium->write('type', 'password', $too_short_password);
	$selenium->write('type', 'passwordconfirm', $too_short_password);	
	$selenium->click('Sign Up');
	$selenium->write('verifyTextPresent', $error);	
	$selenium->write('verifyTextPresent', 'A password must be between 6 and 25 characters long');
	$selenium->write('verifyValue', 'testing_count', $count + 1);	
	//too long password
	$selenium->write('type', 'username', '');
	$selenium->write('type', 'password', $too_long_password);
	$selenium->write('type', 'passwordconfirm', $too_long_password);	
	$selenium->click('Sign Up');
	$selenium->write('verifyTextPresent', $error);	
	$selenium->write('verifyTextPresent', 'A password must be between 6 and 25 characters long');
	$selenium->write('verifyValue', 'testing_count', $count + 1);	
	//bad characters in username
	$selenium->write('type', 'username', $name . '!');
	$selenium->write('type', 'password', $password);
	$selenium->write('type', 'passwordconfirm', $password);	
	$selenium->click('Sign Up');
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A username may only be made up of numbers, letters, and underscores');	
	$selenium->write('verifyValue', 'testing_count', $count + 1);
	//bad characters in password
	$selenium->write('type', 'username', $name);
	$selenium->write('type', 'password', $password . '!');
	$selenium->write('type', 'passwordconfirm', $password . '!');	
	$selenium->click('Sign Up');
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A password may only be made up of numbers, letters, and underscores');	
	$selenium->write('verifyValue', 'testing_count', $count + 1);
	//sign up
	$selenium->openPage('/admin/delete_invite/${invite_key}');
	$selenium->signUp($name, $password, $email);
	$selenium->write('verifyValue', 'testing_count', $count + 4);
	$selenium->signIn($name, $password);
	$selenium->write('verifyValue', 'testing_count', $count + 4);	
	$selenium->openPage('/' . $name);
	$selenium->openPage('/signout');	
	//create a second invite
	//try and create an account with the same username and email
	$selenium->openPage('/admin/request_invite');
	$selenium->write('type', 'email', $email);
	$selenium->write('type', 'emailconfirm', $email);
	$selenium->write('clickAndWait', 'signMeUp');	
	$selenium->write('storeValue', 'key', 'invite_key');		
	$selenium->openPage('/signup/${invite_key}');
	$selenium->write('type', 'username', $name);
	$selenium->write('type', 'realname', $name);	
	$selenium->write('type', 'password', $password);
	$selenium->write('type', 'passwordconfirm', $password);	
	$selenium->write('type', 'email', $email);
	$selenium->click('Sign Up');
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'Username has already been taken');	
	$selenium->write('verifyTextPresent', 'Email is already in use');
	$selenium->write('verifyValue', 'testing_count', $count + 4);	
	$selenium->signIn($name, $password);	
	$selenium->openPage('/admin/delete_invite/${invite_key}');	
	//try to delete account through illegal actions
	$selenium->openPage('/users/delete');
	$selenium->write('verifyTextPresent', $name);
	$selenium->openPage('/settings');	
	//$selenium->write('type', 'update_key', '1');
	//$selenium->write('clickAndWait', 'delete');
	//$selenium->write('verifyConfirmationPresent', 'Are you sure you want to delete your account? This cannot be undone.');
	//$selenium->write('storeConfirmation');
	//$selenium->write('verifyTextPresent', $name);	
	$selenium->write('verifyValue', 'testing_count', $count + 4);	
	//delete account
	$selenium->openPage('/delete_account');
	$selenium->write('clickAndWait', 'delete');
	$selenium->write('verifyConfirmationPresent', 'Are you sure you want to delete your account? This cannot be undone.');
	$selenium->write('storeConfirmation');
	$selenium->write('verifyTextPresent', 'Your account has been deleted.');
	$selenium->openPage('/signin');
	$selenium->write('type', 'username', $name);
	$selenium->write('type', 'password', $password);	
	$selenium->click('Sign In');
	$selenium->write('verifyTextPresent', 'The username and password do not match any in our records.');
	$selenium->write('verifyValue', 'testing_count', $count + 1);	
	$selenium->openPage('/admin/flush');	
?>