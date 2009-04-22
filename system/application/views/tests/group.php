<?php
	$name = $this->selenium->randomString(10);
	$password = $this->selenium->randomString(10);	
	$email = $this->selenium->randomString(10) . '@' . $this->selenium->randomString(10) . '.com';	
	$name2 = $this->selenium->randomString(10);
	$password2 = $this->selenium->randomString(10);	
	$email2 = $this->selenium->randomString(10) . '@' . $this->selenium->randomString(10) . '.com';	
	$group = $this->selenium->randomString(10);
	$group_long = $this->selenium->randomString(16);		
	$group_message = "This is a !$group test.";
	$non_group_message = $this->selenium->randomString(10);	
	$error = 'There was an error adding your group. Please see below for details.';
	$this->selenium->caseTitle('Follow');
	//create first account and sign out
	$this->selenium->openPage('/admin/flush');
	$this->selenium->signOut();	
	$this->selenium->signUp($name, $password, $email);
	$this->selenium->signIn($name, $password);
	//submit an empty form
	$this->selenium->openPage('/groups/add');
	$this->selenium->click('Sign Up');	
	$this->selenium->write('verifyTextPresent', $error);
	$this->selenium->write('verifyTextPresent', 'A group name is required');
	//submit bad characters
	$this->selenium->openPage('/groups/add');
	$this->selenium->write('type', 'name', $group . '!');
	$this->selenium->click('Sign Up');	
	$this->selenium->write('verifyTextPresent', $error);
	$this->selenium->write('verifyTextPresent', 'A group name may only be made up of numbers, letters, and underscores');
	//too long
	$this->selenium->openPage('/groups/add');
	$this->selenium->write('type', 'name', $group_long);
	$this->selenium->click('Sign Up');	
	$this->selenium->write('verifyTextPresent', $error);
	$this->selenium->write('verifyTextPresent', 'A group name must be between 1 and 15 characters');	
	//create a group
	$this->selenium->openPage('/groups/add');
	$this->selenium->write('type', 'name', $group);
	$this->selenium->click('Sign Up');
	$this->selenium->write('verifyTextPresent', $group);
	$this->selenium->write('verifyTextPresent', $name);
	$this->selenium->write('verifyTextPresent', 'Unsubscribe');	
	//try adding a group with the same name
	$this->selenium->openPage('/groups/add');
	$this->selenium->write('type', 'name', $group);
	$this->selenium->click('Sign Up');	
	$this->selenium->write('verifyTextPresent', $error);
	$this->selenium->write('verifyTextPresent', 'Group name has already been taken');	
	//create second account	
	$this->selenium->signOut();	
	$this->selenium->signUp($name2, $password2, $email2);
	$this->selenium->signIn($name2, $password2);
	//subscribe to group
	$this->selenium->openPage('/group/' . $group);	
	$this->selenium->write('verifyTextPresent', $group);
	$this->selenium->write('verifyTextPresent', $name);
	$this->selenium->write('verifyTextPresent', 'Subscribe');	
	$this->selenium->write('clickAndWait', 'link=Subscribe');
	$this->selenium->write('verifyTextPresent', $group);
	$this->selenium->write('verifyTextPresent', $name);
	$this->selenium->write('verifyTextPresent', $name2);	
	//post to group	
	$this->selenium->openPage('/home');
	$this->selenium->write('type', 'message', $group_message);	
	$this->selenium->click('Update');
	//make sure the message is on the group's page
	$this->selenium->openPage('/group/' . $group);
	$this->selenium->write('verifyTextPresent', $group_message);	
	//make sure message is present in user's private page
	$this->selenium->openPage('/home');
	$this->selenium->write('verifyTextPresent', $group_message);
	//make sure message is present in user's public page	
	$this->selenium->openPage('/' . $name2);
	$this->selenium->write('verifyTextPresent', $group_message);	
	//make sure message is present in other user's public page		
	$this->selenium->openPage('/' . $name);	
	$this->selenium->write('verifyTextPresent', $group_message);
	$this->selenium->signOut();	
	$this->selenium->signIn($name, $password);	
	$this->selenium->openPage('/home');		
	//make sure message is present in other user's private page			
	$this->selenium->write('verifyTextPresent', $group_message);
	//make sure message is present timeline		
	$this->selenium->openPage('/public_timeline');		
	$this->selenium->write('verifyTextPresent', $group_message);
	//unsubscribe from group
	//$this->selenium->signOut();	
	//$this->selenium->signIn($name2, $password2);	
	//$this->selenium->openPage('/group/' . $group);	
	//$this->selenium->write('clickAndWait', 'link=Unsubscribe');
	//$this->selenium->write('verifyTextPresent', $group);
	//$this->selenium->write('verifyTextPresent', $name);
	//$this->selenium->write('verifyTextNotPresent', $name2);	
	//$this->selenium->write('verifyTextPresent', 'Subscribe');		
	$this->selenium->openPage('/admin/flush');	
?>