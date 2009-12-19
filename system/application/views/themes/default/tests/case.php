<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);
	$message = $selenium->randomString(10);	
	$message_long = $selenium->randomString(141);
	$email = "nomcat+".$selenium->randomString(10) . '@wearenom.com';
	$error = 'There was an error signing up. Please see below for details.';	
	$count = $this->testingData['count'];
	$group = $selenium->randomString(10);	
	$group_error = 'There was an error adding your group. Please see below for details.';	
	$selenium->caseTitle('Case Sensitivity');
	$selenium->signOut();
	$selenium->signUp($name, $password, $email);
	$selenium->openPage('/' . $name);
	$selenium->write('verifyTextPresent', $name);
	$selenium->openPage('/' . strtoupper($name));
	$selenium->write('verifyTextPresent', $name);
	//create user with same username and email, but different case, and fail
	$selenium->signOut();	
	$selenium->openPage('/admin/request_invite');
	$selenium->write('type', 'email', $email);
	$selenium->write('type', 'emailconfirm', $email);
	$selenium->write('clickAndWait', 'signMeUp');	
	$selenium->write('storeValue', 'key', 'invite_key');
	$selenium->openPage('/signup/${invite_key}');
	$selenium->write('type', 'username', strtoupper($name));
	$selenium->write('type', 'password', $password);
	$selenium->write('type', 'passwordconfirm', $password);	
	$selenium->write('type', 'email', strtoupper($email));
	$selenium->click('Sign Up');
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'Username has already been taken');	
	$selenium->write('verifyTextPresent', 'Email is already in use');
	//create a group
	$selenium->signIn($name, $password);	
	$selenium->openPage('/groups/add');
	$selenium->write('type', 'name', $group);
	$selenium->write('type', 'fullname', $group);
	$selenium->click('Add');
	$selenium->openPage('/group/' . $group);	
	$selenium->write('verifyTextPresent', $group);
	$selenium->openPage('/group/' . strtoupper($group));
	$selenium->write('verifyTextPresent', $group);	
	//try creating the same group name with difference cases, and fail
	$selenium->openPage('/groups/add');
	$selenium->write('type', 'name', strtoupper($group));
	$selenium->write('type', 'fullname', strtoupper($group));	
	$selenium->click('Add');
	$selenium->write('verifyTextPresent', $group_error);
	$selenium->write('verifyTextPresent', 'Group name has already been taken');
	$selenium->write('verifyTextPresent', 'Group full name has already been taken');	
	$selenium->openPage('/admin/flush');			
?>