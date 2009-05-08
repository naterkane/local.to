<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);	
	$email = $selenium->randomString(10) . '@' . $selenium->randomString(10) . '.com';	
	$email_new = $selenium->randomString(10) . '@' . $selenium->randomString(10) . '.com';		
	$group_name = $selenium->randomString(10);
	$group_name_new = $selenium->randomString(10);
	$desc = $selenium->randomString(10);
	$url = $selenium->randomString(10);
	$location = $selenium->randomString(10);	
	$reserved = 'groups';
	$too_long_name = $selenium->randomString(16);
	$too_long_desc = $selenium->randomString(161);	
	$error = 'There was an error updating your group. See below for more details.';	
	$selenium->caseTitle('Change Group Profile');	
	//create account and sign in
	$selenium->signOut();	
	$selenium->signUp($name, $password, $email);
	$selenium->signIn($name, $password);
	//create a group
	$selenium->openPage('/groups/add');
	$selenium->write('type', 'name', $group_name);
	$selenium->click('Add');
	$selenium->write('verifyTextPresent', $group_name);
	//go to settings	
	$selenium->openPage('/groups/settings/' . $group_name);
	$selenium->write('verifyValue', 'name', $group_name);
	//test empty record
	$selenium->write('type', 'name', '');
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A group name is required');
	//try invalid email
	$selenium->write('type', 'email', $name);
	$selenium->click('Update');	
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A valid email is required');
	//try reserved name
	$selenium->write('type', 'name', $reserved);
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $error);	
	$selenium->write('verifyTextPresent', 'This is a reserved name');	
	//too long name
	$selenium->write('type', 'name', $too_long_name);
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $error);	
	$selenium->write('verifyTextPresent', 'A group name must be between 1 and 15 characters');	
	//bad characters in username
	$selenium->write('type', 'name', $group_name . '!');
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A group name may only be made up of numbers, letters, and underscores');	
	//too long description
	$selenium->write('type', 'desc', $too_long_desc);
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A description must be between 1 and 160 characters long');	
	//update record
	$selenium->write('type', 'name', $group_name_new);
	$selenium->write('type', 'email', $email_new);	
	$selenium->write('type', 'desc', $desc);
	$selenium->write('type', 'location', $location);	
	$selenium->write('type', 'url', $url);
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', 'The group was updated.');
	$selenium->write('verifyValue', 'name', $group_name_new);
	$selenium->write('verifyValue', 'email', $email_new);	
	$selenium->write('verifyValue', 'desc', $desc);		
	$selenium->write('verifyValue', 'location', $location);	
	$selenium->write('verifyValue', 'url', $url);
	$selenium->openPage('/admin/flush');	
?>