<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);	
	$email = "nomcat+".$selenium->randomString(10) . '@wearenom.com';
	$name2 = $selenium->randomString(10);
	$password2 = $selenium->randomString(10);	
	$email2 = "nomcat+".$selenium->randomString(10) . '@wearenom.com';
	$name3 = $selenium->randomString(10);
	$password3 = $selenium->randomString(10);	
	$email3 = "nomcat+".$selenium->randomString(10) . '@wearenom.com';	
	$group = $selenium->randomString(10);
	$group_long = $selenium->randomString(16);
	$group_name_full = $selenium->randomString(24) . " " . $selenium->randomString(25);
	$group_name_full_long = $selenium->randomString(51);	
	$group_message = "!$group Hello group.";
	$mention = "!$group " . $selenium->randomString(51);
	$message_from_home = "!$group " . $selenium->randomString(51);	
	$message = $selenium->randomString(20);	
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
	$selenium->write('verifyTextPresent', 'A full group name is required');	
	//submit bad characters
	$selenium->openPage('/groups/add');
	$selenium->write('type', 'name', $group . '!');
	$selenium->click('Add');	
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A group name may only be made up of numbers, letters, and underscores');
	//too long name
	$selenium->openPage('/groups/add');
	$selenium->write('type', 'name', $group_long);
	$selenium->click('Add');	
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A group name must be between 1 and 15 characters');	
	//too long full name
	$selenium->openPage('/groups/add');
	$selenium->write('type', 'name', $group);
	$selenium->write('type', 'fullname', $group_name_full_long);	
	$selenium->click('Add');	
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A full group name must be between 1 and 50 characters');	
	//create a group
	$selenium->openPage('/groups/add');
	$selenium->write('type', 'name', $group);
	$selenium->write('type', 'fullname', $group_name_full);	
	$selenium->click('Add');
	$selenium->openPage('/groups');	
	$selenium->write('verifyTextPresent', $group_name_full);
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
	$selenium->write('type', 'fullname', $group_name_full);	
	$selenium->click('Add');	
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'Group name has already been taken');	
	$selenium->write('verifyTextPresent', 'Group full name has already been taken');		
	//subscribe a member
	$selenium->openPage('/groups/invites/' . $group);		
	$selenium->write('type', 'invites', $email3);
	$selenium->click('Create Invitations');	
	$selenium->write('verifyTextPresent', '1 invites(s) added.');
	$selenium->write('verifyElementPresent', 'count-0');
	$selenium->write('verifyElementNotPresent', 'count-1');	
	$selenium->write('storeValue', 'count-0', 'key1');
	//sign in as user and accept invite
	$selenium->signOut();	
	$selenium->signUp($name3, $password3, $email3);
	$selenium->signIn($name3, $password3);
	$selenium->openPage('/groups/accept/${key1}');
	//post a message to the group from the group page and make sure it appears on the group page and the user's home, not on the mentions, not on the public timeline
	$selenium->openPage('/group/' . $group);
	$selenium->write('type', 'message', $message);
	$selenium->click('Update');	
	$selenium->openPage('/group/' . $group);
	$selenium->write('verifyTextPresent', $message);
	$selenium->openPage('/home');
	$selenium->write('verifyTextPresent', $message);	
	$selenium->openPage('/public_timeline');
	$selenium->write('verifyTextNotPresent', $message);	
	$selenium->openPage('/groups/mentions/' . $group);
	$selenium->write('verifyTextNotPresent', $message);	
	//post a message to the group from the user home page page and make sure it appears on the user's home and the mentions and public timeline, not on the group home
	$selenium->openPage('/home');
	$selenium->write('type', 'message', $message_from_home);
	$selenium->click('Update');
	$selenium->openPage('/group/' . $group);
	$selenium->write('verifyTextNotPresent', $message_from_home);
	$selenium->openPage('/home');
	$selenium->write('verifyTextPresent', $message_from_home);	
	$selenium->openPage('/public_timeline');
	$selenium->write('verifyTextPresent', $message_from_home);	
	$selenium->openPage('/groups/mentions/' . $group);
	$selenium->write('verifyTextPresent', $message_from_home);	
	//make sure first user gets the first message, not the second
	$selenium->signOut();
	$selenium->signIn($name, $password);
	$selenium->openPage('/home');
	$selenium->write('verifyTextPresent', $message);
	$selenium->write('verifyTextNotPresent', $message_from_home);	
	//create second account	
	$selenium->signOut();	
	$selenium->signUp($name2, $password2, $email2);
	$selenium->signIn($name2, $password2);
	//check that settings are not accessible and message is not visible
	$selenium->openPage('/group/' . $group);
	$selenium->write('verifyTextNotPresent', '(Settings)');
	$selenium->write('openAndWait', '/groups/settings/' . $group);
	$selenium->write('verifyTextPresent', $selenium->missingText);
	$selenium->openPage('/home');
	$selenium->write('verifyTextNotPresent', $message);	
	//post a message to the group and have it show up as a mention
	$selenium->write('type', 'message', $mention);
	$selenium->click('Update');	
	$selenium->signOut();	
	$selenium->signIn($name, $password);	
	$selenium->openPage('/groups/mentions/' . $group);
	$selenium->write('verifyTextPresent', $mention);
	$selenium->openPage('/group/' . $group);
	$selenium->write('verifyTextNotPresent', $mention);
	$selenium->openPage('/home');
	$selenium->write('verifyTextNotPresent', $mention);
	//make sure user can not send to non-existent team
	$selenium->openPage('/admin/flush');	
?>