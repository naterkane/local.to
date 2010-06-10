<?php
	$name = $selenium->randomString(10);
	$name2 = $selenium->randomString(10);	
	$password = $selenium->randomString(10);
	$password2 = $selenium->randomString(10);	
	$message = $selenium->randomString(10);
	$message2 = $selenium->randomString(10);	
	$message_long = $selenium->randomString(141);
	$email = "nomcat+".$selenium->randomString(10) . '@wearenom.com';	
	$email2 = "nomcat+".$selenium->randomString(10) . '@wearenom.com';		
	$count = $this->testingData['count'];		
	$selenium->caseTitle('Direct Message');	
	$selenium->signOut();
	$selenium->write('verifyValue', 'testing_count', $count);	
	//sign up two accounts
	$selenium->signUp($name, $password, $email);
	$selenium->write('verifyValue', 'testing_count', $count + 4);	
	$selenium->signOut();
	$selenium->signUp($name2, $password2, $email2);	
	$selenium->signIn($name2, $password2);
	$selenium->write('verifyValue', 'testing_count', $count + 7);
	//follow first account
	$selenium->openPage('/' . $name);		
	$selenium->write('clickAndWait', 'follow');
	$selenium->write('verifyValue', 'testing_count', $count + 7);
	$selenium->write('verifyTextPresent', 'Stop Following');
	//check to see no one appears as second users followers
	$selenium->openPage('/inbox');
	$selenium->write('verifyElementNotPresent', 'to');	
	$selenium->openPage('/sent');
	$selenium->write('verifyElementNotPresent', 'to');	
	//sign out and log in as first user
	$selenium->signOut();
	$selenium->signIn($name, $password);
	//try send a dm without a user
	$selenium->openPage('/inbox');
	$selenium->write('type', 'message', $message);
	$selenium->click('Update');
	$selenium->openPage('/sent');	
	$selenium->write('verifyTextNotPresent', $message);	
	$selenium->write('verifyValue', 'testing_count', $count + 8);
	//try send a too long dm
	$selenium->write('type', 'message', $message_long);
	$selenium->write('select', 'to', $name2);	
	$selenium->click('Update');	
	$selenium->openPage('/sent');
	$selenium->write('verifyTextNotPresent', $message);	
	$selenium->write('verifyValue', 'testing_count', $count + 8);	
	//try to send a dm with no message
	$selenium->openPage('/inbox');
	$selenium->write('type', 'message', '');
	$selenium->write('select', 'to', $name2);
	$selenium->click('Update');
	$selenium->openPage('/sent');
	$selenium->write('verifyTextNotPresent', $message);	
	$selenium->write('verifyValue', 'testing_count', $count + 8);		
	//send a dm to the second user
	$selenium->openPage('/inbox');
	$selenium->write('type', 'message', $message);
	$selenium->write('select', 'to', $name2);
	$selenium->click('Update');
	$selenium->openPage('/public_timeline');
	$selenium->write('verifyValue', 'testing_count', $count + 9);
	$selenium->write('verifyTextNotPresent', $message);
	$selenium->openPage('/' . $name);
	$selenium->write('verifyTextNotPresent', $message);
	$selenium->openPage('/home');
	$selenium->write('verifyTextNotPresent', $message);
	$selenium->openPage('/' . $name2);
	$selenium->write('verifyTextNotPresent', $message);	
	$selenium->openPage('/inbox');
	$selenium->write('verifyTextNotPresent', $message);	
	$selenium->openPage('/sent');
	$selenium->write('verifyTextPresent', $message);	
	//sign in as second user and check inbox
	$selenium->signOut();
	$selenium->signIn($name2, $password2);	
	$selenium->openPage('/home');
	$selenium->write('verifyTextNotPresent', $message);
	$selenium->openPage('/sent');
	$selenium->write('verifyTextNotPresent', $message);	
	$selenium->openPage('/inbox');
	$selenium->write('verifyTextPresent', $message);
	//sign in as first user and send a dm to the send user with tags
	$selenium->signOut();
	$selenium->signIn($name, $password);	
	$selenium->openPage('/home');	
	$selenium->write('type', 'message', 'd ' . $name2 . ' ' . $message2);
	$selenium->click('Update');
	$selenium->openPage('/public_timeline');
	$selenium->write('verifyValue', 'testing_count', $count + 10);
	$selenium->write('verifyTextNotPresent', $message2);
	$selenium->openPage('/' . $name);
	$selenium->write('verifyTextNotPresent', $message2);
	$selenium->openPage('/home');
	$selenium->write('verifyTextNotPresent', $message2);
	$selenium->openPage('/' . $name2);
	$selenium->write('verifyTextNotPresent', $message2);	
	$selenium->openPage('/inbox');
	$selenium->write('verifyTextNotPresent', $message2);	
	$selenium->openPage('/sent');
	$selenium->write('verifyTextPresent', $message2);	
	//sign in as second user and check inbox
	$selenium->signOut();
	$selenium->signIn($name2, $password2);	
	$selenium->openPage('/home');
	$selenium->write('verifyTextNotPresent', $message2);
	$selenium->openPage('/sent');
	$selenium->write('verifyTextNotPresent', $message2);	
	$selenium->openPage('/inbox');
	$selenium->write('verifyTextPresent', $message2);
	$selenium->openPage('/admin/flush');
?>