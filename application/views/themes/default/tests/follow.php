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
	$message = $selenium->randomString(10);
	$message2 = $selenium->randomString(10);	
	$message3 = $selenium->randomString(10);					
	$count = $this->testingData['count'];	
	$selenium->caseTitle('Follow');	
	//create first account and sign out
	$selenium->signOut();	
	$selenium->write('verifyValue', 'testing_count', $count);	
	$selenium->signUp($name, $password, $email);
	$selenium->write('verifyValue', 'testing_count', $count + 4);	
	$selenium->signIn($name, $password);
	$selenium->signOut();	
	//lock settings
	$selenium->signOut();	
	$selenium->signIn($name, $password);
	$selenium->openPage('/settings');
	$selenium->write('assertNotChecked', 'locked');		
	$selenium->write('click', 'locked');
	$selenium->click('Update');
	$selenium->write('verifyValue', 'testing_count', $count + 4);
	$selenium->write('verifyTextPresent', 'Your profile was updated.');
	$selenium->write('assertChecked', 'locked');	
	//create second account	
	$selenium->signOut();	
	$selenium->signUp($name2, $password2, $email2);
	$selenium->write('verifyValue', 'testing_count', $count + 7);
	$selenium->signIn($name2, $password2);
	//check follower counts
	$selenium->openPage('/home');
	$selenium->write('verifyTextPresent', 'Following: 0 Followers: 0');
	//follow first account
	$selenium->openPage('/' . $name);		
	$selenium->write('clickAndWait', 'follow');
	$selenium->write('verifyValue', 'testing_count', $count + 7);
	$selenium->write('verifyTextPresent', 'A confirmation request has been sent to ' . $name . '.');
	$selenium->write('verifyTextPresent', 'You have a submitted a friend request to');	
	//try to follow a second time and get error
	$selenium->write('openAndWait', '/follow/' . $name);	
	$selenium->write('verifyTextPresent', $selenium->missingText);	
	//try to follow yourself
	$selenium->write('openAndWait', '/follow/' . $name2);	
	$selenium->write('verifyTextPresent', $selenium->missingText);	
	$selenium->signOut();		
	$selenium->write('verifyValue', 'testing_count', $count + 7);	
	//go to first user's home page
	$selenium->signIn($name, $password);	
	$selenium->openPage('/home');	
	$selenium->write('verifyTextPresent', 'Following: 0 Followers: 0');
	//accept friend request
	$selenium->openPage('/friend_requests');	
	$selenium->write('verifyTextPresent', $name2);
	$selenium->write('clickAndWait', 'confirm' . $name2);	
	$selenium->write('verifyValue', 'testing_count', $count + 7);
	$selenium->write('verifyTextPresent', $name2 . ' is now following your posts.');
	$selenium->write('verifyTextPresent', 'Following: 0 Followers: 1');
	//try and accept a friend request from someone who did not request
	$selenium->write('openAndWait', '/confirm/1');	
	$selenium->write('verifyValue', 'testing_count', $count + 7);
	$selenium->write('verifyTextPresent', 'Following: 0 Followers: 1');	
	$selenium->write('verifyTextPresent', 'There was problem adding this follower');	
	//post a message
	$selenium->write('type', 'message', $message);
	$selenium->click('Update');
	$selenium->write('verifyValue', 'testing_count', $count + 9);
	$selenium->openPage('/');
	$selenium->write('verifyTextPresent', $message);
	$selenium->signOut();		
	//make sure second account sees post and is following
	$selenium->signIn($name2, $password2);
	$selenium->openPage('/home');	
	$selenium->write('verifyTextPresent', $message);
	$selenium->write('verifyTextPresent', 'Following: 1 Followers: 0');	
	$selenium->openPage('/' . $name2);	
	$selenium->write('verifyTextNotPresent', $message);	
	//unsubscribe
	$selenium->openPage('/' . $name);		
	$selenium->write('clickAndWait', 'unfollow');
	$selenium->write('verifyValue', 'testing_count', $count + 9);
	$selenium->write('verifyTextPresent', 'Follow');
	$selenium->openPage('/home');	
	$selenium->write('verifyTextPresent', 'Following: 0 Followers: 0');	
	$selenium->signOut();	
	//Post again as first
	$selenium->signIn($name, $password);
	$selenium->openPage('/home');	
	$selenium->write('verifyTextPresent', 'Following: 0 Followers: 0');	
	$selenium->write('type', 'message', $message2);
	$selenium->click('Update');
	$selenium->write('verifyValue', 'testing_count', $count + 10);
	$selenium->openPage('/');
	$selenium->write('verifyTextPresent', $message2);
	$selenium->signOut();	
	//make sure second message is not there
	$selenium->signIn($name2, $password2);
	$selenium->openPage('/home');	
	$selenium->write('verifyTextNotPresent', $message2);
	$selenium->openPage('/' . $name2);	
	$selenium->write('verifyTextNotPresent', $message2);	
	//unlock settings
	$selenium->signOut();	
	$selenium->signIn($name, $password);
	$selenium->openPage('/settings');
	$selenium->write('assertChecked', 'locked');		
	$selenium->write('click', 'locked');
	$selenium->click('Update');
	$selenium->write('verifyValue', 'testing_count', $count + 10);
	$selenium->write('verifyTextPresent', 'Your profile was updated.');
	$selenium->write('assertNotChecked', 'locked');			
	//friend without confirmation
	$selenium->signOut();	
	$selenium->signIn($name2, $password2);		
	$selenium->openPage('/' . $name);		
	$selenium->write('clickAndWait', 'follow');
	$selenium->write('verifyValue', 'testing_count', $count + 10);
	$selenium->write('verifyTextPresent', 'Stop Following');
	$selenium->openPage('/home');
	$selenium->write('verifyTextPresent', 'Following: 1 Followers: 0');	
	//sign out and post from first account
	$selenium->signOut();	
	$selenium->signIn($name, $password);
	$selenium->openPage('/home');
	$selenium->write('verifyTextPresent', 'Following: 0 Followers: 1');	
	$selenium->write('type', 'message', $message3);
	$selenium->click('Update');
	$selenium->write('verifyValue', 'testing_count', $count + 12);
	$selenium->openPage('/');
	$selenium->write('verifyTextPresent', $message3);
	$selenium->signOut();
	//sign in and check if second account sees message		
	$selenium->signOut();	
	$selenium->signIn($name2, $password2);		
	$selenium->write('verifyTextPresent', $message3);
	//create a third account and send the first a friend request
	$selenium->signOut();	
	$selenium->signIn($name, $password);
	$selenium->openPage('/settings');
	$selenium->write('assertNotChecked', 'locked');	
	$selenium->write('click', 'locked');
	$selenium->click('Update');
	$selenium->write('verifyValue', 'testing_count', $count + 12);
	$selenium->write('verifyTextPresent', 'Your profile was updated.');
	$selenium->write('assertChecked', 'locked');	
	$selenium->signOut();	
	$selenium->signUp($name3, $password3, $email3);
	$selenium->write('verifyValue', 'testing_count', $count + 15);	
	$selenium->signIn($name3, $password3);
	$selenium->openPage('/' . $name);
	$selenium->write('clickAndWait', 'follow');
	$selenium->write('verifyValue', 'testing_count', $count + 15);
	$selenium->write('verifyTextPresent', 'You have a submitted a friend request to '. $name .', it is currently pending.');
	$selenium->signOut();	
	$selenium->signIn($name, $password);			
	$selenium->openPage('/friend_requests');	
	$selenium->write('clickAndWait', 'deny' . $name3);
	$selenium->write('verifyTextPresent', 'User request denied.');	
	$selenium->write('verifyValue', 'testing_count', $count + 15);	
	$selenium->openPage('/home');
	$selenium->write('verifyTextPresent', 'Following: 0 Followers: 1');
	$selenium->openPage('/admin/flush');	
?>