<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);	
	$email = "nomcat+".$selenium->randomString(10) . '@wearenom.com';
	$name2 = $selenium->randomString(10);
	$password2 = $selenium->randomString(10);	
	$email2 = "nomcat+".$selenium->randomString(10) . '@wearenom.com';
	$group = $selenium->randomString(10);
	$group_long = $selenium->randomString(16);		
	$group_message = $selenium->randomString(16);
	$non_group_message = $selenium->randomString(10);	
	$error = 'There was an error adding your group. Please see below for details.';
	$selenium->caseTitle('Follow');
	//create first account and sign out
	$selenium->openPage('/admin/flush');
	$selenium->signOut();	
	$selenium->signUp($name, $password, $email);
	$selenium->signIn($name, $password);
	//create a group
	$selenium->openPage('/groups/add');
	$selenium->write('type', 'name', $group);
	$selenium->click('Add');
	$selenium->write('verifyTextPresent', $group);
	$selenium->openPage('/groups/members/' . $group);	
	$selenium->write('verifyTextPresent', $name);	
	//create second account	
	$selenium->signOut();	
	$selenium->signUp($name2, $password2, $email2);
	$selenium->signIn($name2, $password2);
	//try accessing group inbox
	$selenium->write('openAndWait', '/groups/inbox/' . $group);
	$selenium->write('verifyTextPresent', $selenium->missingText);
	//try posting to group
	//post dm to group	
	$selenium->openPage('/home');
	$selenium->write('type', 'message', "d !" . $group . " " . $group_message);	
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', 'There was an error adding your message.');			
	//make sure message is not present in user's inbox	
	$selenium->openPage('/inbox');
	$selenium->write('verifyTextNotPresent', $group_message);
	//make sure message is not present in user's sent box
	$selenium->openPage('/sent');
	$selenium->write('verifyTextNotPresent', $group_message);	
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
	//post dm to group	
	$selenium->write('verifyTextNotPresent', $group_message);	
	$selenium->openPage('/groups/inbox/' . $group);
	$selenium->write('type', 'message', "d !" . $group . " " . $group_message);	
	$selenium->click('Update');	
	//make sure the message is on the group's inbox
	$selenium->openPage('/groups/inbox/' . $group);
	$selenium->write('verifyTextPresent', $group_message);
	//make sure message is present in user's inbox
	$selenium->openPage('/inbox');
	$selenium->write('verifyTextPresent', $group_message);
	//make sure message is present in user's sent box
	$selenium->openPage('/sent');
	$selenium->write('verifyTextPresent', $group_message);	
	//make sure message is not present in user's private page
	$selenium->openPage('/home');
	$selenium->write('verifyTextNotPresent', $group_message);
	//make sure message is not present in user's public page
	$selenium->openPage('/' . $name2);
	$selenium->write('verifyTextNotPresent', $group_message);	
	//make sure message is not present in public timeline
	$selenium->openPage('/public_timeline');
	$selenium->write('verifyTextNotPresent', $group_message);	
	//make sure message is not present in group public page
	$selenium->openPage('/group/' . $group);
	$selenium->write('verifyTextNotPresent', $group_message);	
	//make sure message is not present in other user's public page		
	$selenium->openPage('/' . $name);	
	$selenium->write('verifyTextNotPresent', $group_message);
	$selenium->signOut();	
	$selenium->signIn($name, $password);
	//make sure message is not present in other user's private page					
	$selenium->openPage('/home');		
	$selenium->write('verifyTextNotPresent', $group_message);
	//make sure message is present in other user's inbox					
	$selenium->openPage('/inbox');
	$selenium->write('verifyTextPresent', $group_message);	
	//make sure message is not present in other user's sent
	$selenium->openPage('/sent');
	$selenium->write('verifyTextNotPresent', $group_message);	
?>