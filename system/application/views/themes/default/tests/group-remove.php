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
	$group_message = "This is a !$group test.";
	$non_group_message = $selenium->randomString(10);	
	$error = 'There was an error adding your group. Please see below for details.';
	$count = $this->testingData['count'];			
	$selenium->caseTitle('Follow');
	//create first account and sign out
	$selenium->openPage('/admin/flush');
	$selenium->signOut();
	$selenium->write('verifyValue', 'testing_count', $count);		
	$selenium->signUp($name, $password, $email);
	$selenium->write('verifyValue', 'testing_count', $count + 4);	
	$selenium->signIn($name, $password);
	//create a group
	$selenium->openPage('/groups/add');
	$selenium->write('type', 'name', $group);
	$selenium->write('type', 'fullname', $group_name_full);	
	$selenium->click('Add');
	$selenium->write('verifyTextPresent', $group);
	$selenium->openPage('/groups/members/' . $group);
	$selenium->write('verifyValue', 'testing_count', $count + 9);
	$selenium->write('verifyTextPresent', $name);	
	//subscribe one user
	$selenium->openPage('/groups/invites/' . $group);		
	$selenium->write('type', 'invites', $email2);
	$selenium->click('Create Invitations');	
	$selenium->write('verifyTextPresent', '1 invites(s) added.');
	$selenium->write('storeValue', 'count-0', 'key2');		
	$selenium->signOut();
	$selenium->signUp($name2, $password2, $email2);	
	$selenium->write('verifyValue', 'testing_count', $count + 13);
	$selenium->signIn($name2, $password2);
	$selenium->openPage('/groups/accept/${key2}');
	//sign in as third user, try  to delte second user's membership, fail
	$selenium->signUp($name3, $password3, $email3);
	$selenium->write('verifyValue', 'testing_count', $count + 18);	
	$selenium->signIn($name3, $password3);
	$selenium->open404('/groups/remove/1/2');
	$selenium->signOut();
	//sign in as owner
	$selenium->signIn($name, $password);
	//check malformed url produces 404
	$selenium->open404('/groups/remove/1');	
	$selenium->open404('/groups/remove');
	$selenium->open404('/groups/remove/khdsfhjdsf');	
	//check that the owner can not remove herself
	$selenium->open404('/groups/remove/1/1');		
	//remove first subscriber
	$selenium->openPage('/groups/members/' . $group);	
	$selenium->write('clickAndWait', 'remove' . $name2);
	$selenium->write('assertConfirmation', 'Are sure you want to want to remove this member?');	
	$selenium->write('verifyTextPresent', 'The member was removed.');
	$selenium->write('verifyValue', 'testing_count', $count + 18);	
	//re-subscribe first user
	$selenium->openPage('/groups/invites/' . $group);	
	$selenium->write('type', 'invites', $email2);
	$selenium->click('Create Invitations');	
	$selenium->write('verifyValue', 'testing_count', $count + 19);
	$selenium->write('verifyTextPresent', '1 invites(s) added.');
	$selenium->write('storeValue', 'count-0', 'key3');		
	$selenium->signOut();	
	$selenium->signIn($name2, $password2);
	$selenium->openPage('/groups/accept/${key3}');
	$selenium->signOut();	
	//blacklist first user
	$selenium->signIn($name, $password);
	$selenium->openPage('/groups/members/' . $group);	
	$selenium->write('clickAndWait', 'blacklist' . $name2);
	$selenium->write('assertConfirmation', 'Are sure you want to want to blacklist this member?');	
	$selenium->write('verifyTextPresent', 'The member was removed and blacklisted.');		
	$selenium->write('verifyValue', 'testing_count', $count + 19);	
	$selenium->write('verifyTextNotPresent', $name2);
	$selenium->openPage('/groups/blacklist/' . $group);	
	$selenium->write('verifyTextPresent', $name2);	
	//try to sign up again as blacklisted user 
	$selenium->openPage('/groups/invites/' . $group);	
	$selenium->write('type', 'invites', $email2);
	$selenium->click('Create Invitations');	
	$selenium->write('verifyTextPresent', '1 invites(s) added.');
	$selenium->write('verifyValue', 'testing_count', $count + 20);	
	$selenium->write('storeValue', 'count-0', 'key4');		
	$selenium->signOut();	
	$selenium->signIn($name2, $password2);
	$selenium->openPage('/groups/accept/${key4}');	
	$selenium->write('verifyTextPresent', 'There was a problem joining this group');
	$selenium->write('verifyValue', 'testing_count', $count + 20);	
	$selenium->signOut();	
	$selenium->signIn($name, $password);	
	$selenium->openPage('/groups/members/' . $group);		
	$selenium->write('verifyTextNotPresent', $name2);	
	//remove from blacklist and sign up
	$selenium->openPage('/groups/blacklist/' . $group);	
	$selenium->write('clickAndWait', 'unblacklist' . $name2);
	$selenium->write('assertConfirmation', 'Are you sure you want to remove the user from the blacklist?');
	$selenium->write('verifyTextPresent', 'The user was removed from the blacklist.');	
	$selenium->write('verifyValue', 'testing_count', $count + 20);	
	$selenium->signOut();
	$selenium->signIn($name2, $password2);
	$selenium->openPage('/groups/accept/${key4}');
	$selenium->signOut();	
	$selenium->signIn($name, $password);	
	$selenium->openPage('/groups/members/' . $group);					
	$selenium->write('verifyValue', 'testing_count', $count + 20);	
	$selenium->write('verifyTextPresent', $name2);		
	$selenium->openPage('/admin/flush');
?>