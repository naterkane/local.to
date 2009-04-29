<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);	
	$email = $selenium->randomString(10) . '@' . $selenium->randomString(10) . '.com';	
	$name2 = $selenium->randomString(10);
	$password2 = $selenium->randomString(10);	
	$email2 = $selenium->randomString(10) . '@' . $selenium->randomString(10) . '.com';	
	$group = $selenium->randomString(10);
	$group_long = $selenium->randomString(16);		
	$group_message = "This is a !$group test.";
	$non_group_message = $selenium->randomString(10);	
	$error = 'There was an error adding your group. Please see below for details.';
	$selenium->caseTitle('Follow');
	//create first account and sign out
	$selenium->openPage('/admin/flush');
	$selenium->signOut();	
	$selenium->signUp($name, $password, $email);
	$selenium->signIn($name, $password);
	//submit an empty form
	$selenium->openPage('/groups/add');
	$selenium->click('Add');	
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A group name is required');
	//submit bad characters
	$selenium->openPage('/groups/add');
	$selenium->write('type', 'name', $group . '!');
	$selenium->click('Add');	
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A group name may only be made up of numbers, letters, and underscores');
	//too long
	$selenium->openPage('/groups/add');
	$selenium->write('type', 'name', $group_long);
	$selenium->click('Add');	
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A group name must be between 1 and 15 characters');	
	//create a group
	$selenium->openPage('/groups/add');
	$selenium->write('type', 'name', $group);
	$selenium->click('Add');
	$selenium->write('verifyTextPresent', $group);
	$selenium->openPage('/groups/members/' . $group);		
	$selenium->write('verifyTextPresent', $name);	
	//check that settings are accessible
	$selenium->openPage('/group/' . $group);		
	$selenium->write('verifyTextPresent', 'Settings');
	$selenium->openPage('/groups/settings/' . $group);		
	$selenium->write('verifyTextPresent', $group);	
	//try adding a group with the same name
	$selenium->openPage('/groups/add');
	$selenium->write('type', 'name', $group);
	$selenium->click('Add');	
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'Group name has already been taken');	
	//create second account	
	$selenium->signOut();	
	$selenium->signUp($name2, $password2, $email2);
	$selenium->signIn($name2, $password2);
	//check that settings are not accessible
	$selenium->openPage('/group/' . $group);	
	$selenium->write('verifyTextNotPresent', '(Settings)');
	$selenium->write('openAndWait', '/groups/settings/' . $group);
	$selenium->write('verifyTextPresent', $selenium->missingText);	
	//subscribe to group
	$selenium->openPage('/group/' . $group);	
	$selenium->write('verifyTextPresent', $group);
	$selenium->write('verifyTextPresent', 'Subscribe');	
	$selenium->write('clickAndWait', 'link=Subscribe');
	$selenium->openPage('/groups/members/' . $group);	
	$selenium->write('verifyTextPresent', $group);	
	$selenium->write('verifyTextPresent', $name);
	$selenium->write('verifyTextPresent', $name2);
	$selenium->openPage('/group/' . $group);	
	$selenium->write('verifyTextPresent', 'Unsubscribe');						
	//post to group	
	$selenium->openPage('/home');
	$selenium->write('type', 'message', $group_message);	
	$selenium->click('Update');
	//make sure the message is on the group's page
	$selenium->openPage('/group/' . $group);
	$selenium->write('verifyTextPresent', $group_message);	
	//make sure message is present in user's private page
	$selenium->openPage('/home');
	$selenium->write('verifyTextPresent', $group_message);
	//make sure message is present in user's public page	
	$selenium->openPage('/' . $name2);
	$selenium->write('verifyTextPresent', $group_message);	
	//make sure message is present in other user's public page		
	$selenium->openPage('/' . $name);	
	$selenium->write('verifyTextPresent', $group_message);
	$selenium->signOut();	
	$selenium->signIn($name, $password);	
	$selenium->openPage('/home');		
	//make sure message is present in other user's private page			
	$selenium->write('verifyTextPresent', $group_message);
	//make sure message is present timeline		
	$selenium->openPage('/public_timeline');		
	$selenium->write('verifyTextNotPresent', $group_message);
	//unsubscribe from group
	$selenium->signOut();	
	$selenium->signIn($name2, $password2);	
	$selenium->openPage('/group/' . $group);	
	$selenium->write('clickAndWait', 'link=Unsubscribe');
	$selenium->openPage('/groups/members/' . $group);		
	$selenium->write('verifyTextPresent', $group);
	$selenium->write('verifyTextPresent', $name);
	$selenium->write('verifyTextNotPresent', $name2);	
	$selenium->openPage('/group/' . $group);			
	$selenium->write('verifyTextPresent', 'Subscribe');	
	//check to see if messages are still on group's home page	
	$selenium->openPage('/group/' . $group);
	$selenium->write('verifyTextPresent', $group_message);	
	$selenium->openPage('/admin/flush');	
?>