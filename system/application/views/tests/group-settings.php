<?php
	$name = $this->selenium->randomString(10);
	$password = $this->selenium->randomString(10);	
	$email = $this->selenium->randomString(10) . '@' . $this->selenium->randomString(10) . '.com';	
	$email_new = $this->selenium->randomString(10) . '@' . $this->selenium->randomString(10) . '.com';		
	$group_name = $this->selenium->randomString(10);
	$group_name_new = $this->selenium->randomString(10);
	$desc = $this->selenium->randomString(10);
	$url = $this->selenium->randomString(10);
	$location = $this->selenium->randomString(10);	
	$reserved = 'groups';
	$too_long_name = $this->selenium->randomString(16);
	$too_long_desc = $this->selenium->randomString(161);	
	$error = 'There was an error updating your group. See below for more details.';	
	$this->selenium->caseTitle('Change Group Profile');
	//create account and sign in
	$this->selenium->signOut();	
	$this->selenium->signUp($name, $password, $email);
	$this->selenium->signIn($name, $password);
	//create a group
	$this->selenium->openPage('/groups/add');
	$this->selenium->write('type', 'name', $group_name);
	$this->selenium->click('Add');
	$this->selenium->write('verifyTextPresent', $group_name);
	//go to settings	
	$this->selenium->openPage('/groups/settings/' . $group_name);
	$this->selenium->write('verifyValue', 'name', $group_name);
	//test empty record
	$this->selenium->write('type', 'name', '');
	$this->selenium->click('Update');
	$this->selenium->write('verifyTextPresent', $error);
	$this->selenium->write('verifyTextPresent', 'A name is required');
	//try invalid email
	$this->selenium->write('type', 'email', $name);
	$this->selenium->click('Update');	
	$this->selenium->write('verifyTextPresent', $error);
	$this->selenium->write('verifyTextPresent', 'A valid email is required');
	//try reserved name
	$this->selenium->write('type', 'name', $reserved);
	$this->selenium->click('Update');
	$this->selenium->write('verifyTextPresent', $error);	
	$this->selenium->write('verifyTextPresent', 'This is a reserved name');	
	//too long name
	$this->selenium->write('type', 'name', $too_long_name);
	$this->selenium->click('Update');
	$this->selenium->write('verifyTextPresent', $error);	
	$this->selenium->write('verifyTextPresent', 'A group name must be between 1 and 15 characters long');	
	//bad characters in username
	$this->selenium->write('type', 'name', $group_name . '!');
	$this->selenium->click('Update');
	$this->selenium->write('verifyTextPresent', $error);
	$this->selenium->write('verifyTextPresent', 'A group name may only be made up of numbers, letters, and underscores');	
	//too long description
	$this->selenium->write('type', 'desc', $too_long_desc);
	$this->selenium->click('Update');
	$this->selenium->write('verifyTextPresent', $error);
	$this->selenium->write('verifyTextPresent', 'A description must be between 1 and 160 characters long');	
	//update record
	$this->selenium->write('type', 'name', $group_name_new);
	$this->selenium->write('type', 'email', $email_new);	
	$this->selenium->write('type', 'desc', $desc);
	$this->selenium->write('type', 'location', $location);	
	$this->selenium->write('type', 'url', $url);
	$this->selenium->click('Update');
	$this->selenium->write('verifyTextPresent', 'The group was updated.');
	$this->selenium->write('verifyValue', 'name', $group_name_new);
	$this->selenium->write('verifyValue', 'email', $email_new);	
	$this->selenium->write('verifyValue', 'desc', $desc);		
	$this->selenium->write('verifyValue', 'location', $location);	
	$this->selenium->write('verifyValue', 'url', $url);
	$this->selenium->openPage('/admin/flush');	
?>