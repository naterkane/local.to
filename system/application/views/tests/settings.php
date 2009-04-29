<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);	
	$email = $selenium->randomString(10) . '@' . $selenium->randomString(10) . '.com';	
	$name_new = $selenium->randomString(10);
	$email_new = $selenium->randomString(10) . '@' . $selenium->randomString(10) . '.com';	
	$bio = $selenium->randomString(10);
	$url = $selenium->randomString(10);
	$location = $selenium->randomString(10);	
	$real_name = $selenium->randomString(10);	
	$reserved = 'groups';	
	$too_long_name = $selenium->randomString(16);
	$too_long_bio = $selenium->randomString(161);	
	$error = 'There was an error updating your profile. See below for more details.';	
	$selenium->caseTitle('Change Profile');
	//create account and sign in
	$selenium->signOut();	
	$selenium->signUp($name, $password, $email);
	$selenium->signIn($name, $password);
	//go to settings	
	$selenium->openPage('/settings');
	$selenium->write('verifyValue', 'username', $name);
	$selenium->write('verifyValue', 'email', $email);
	//test empty record
	$selenium->write('type', 'username', '');
	$selenium->write('type', 'email', '');	
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A username is required');
	$selenium->write('verifyTextPresent', 'A valid email is required');
	//try invalid email
	$selenium->write('type', 'email', $name);	
	$selenium->click('Update');	
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A valid email is required');
	//try reserved username
	$selenium->write('type', 'username', $reserved);
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $error);	
	$selenium->write('verifyTextPresent', 'This is a reserved username');	
	//too long username
	$selenium->write('type', 'username', $too_long_name);
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $error);	
	$selenium->write('verifyTextPresent', 'A username must be between 1 and 15 characters long');	
	//bad characters in username
	$selenium->write('type', 'username', $name . '!');
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A username may only be made up of numbers, letters, and underscores');	
	//too long bio
	$selenium->write('type', 'bio', $too_long_bio);
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $error);	
	$selenium->write('verifyTextPresent', 'A bio must be between 1 and 160 characters long');	
	//update record
	$selenium->write('type', 'username', $name_new);
	$selenium->write('type', 'email', $email_new);	
	$selenium->write('type', 'bio', $bio);		
	$selenium->write('type', 'realname', $real_name);
	$selenium->write('type', 'location', $location);	
	$selenium->write('type', 'url', $url);
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', 'Your profile was updated.');
	$selenium->openPage('/settings');	
	$selenium->write('verifyValue', 'username', $name_new);
	$selenium->write('verifyValue', 'email', $email_new);	
	$selenium->write('verifyValue', 'bio', $bio);		
	$selenium->write('verifyValue', 'realname', $real_name);
	$selenium->write('verifyValue', 'location', $location);	
	$selenium->write('verifyValue', 'url', $url);		
	$selenium->openPage('/admin/flush');	
?>