<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);
	$name2 = $selenium->randomString(10);
	$password2 = $selenium->randomString(10);	
	$message = $selenium->randomString(10);
	$message2 = $selenium->randomString(10);
	$message3 = $selenium->randomString(10);		
	$message_long = $selenium->randomString(141);	
	$email = "nomcat+".$selenium->randomString(10) . '@wearenom.com';	
	$email2 = "nomcat+".$selenium->randomString(10) . '@wearenom.com';		
	$count = $this->testingData['count'];		
	$selenium->caseTitle('Message');	
	$selenium->signOut();	
	$selenium->write('verifyValue', 'testing_count', $count);	
	$selenium->signUp($name, $password, $email);
	$selenium->signIn($name, $password);
	$selenium->write('verifyValue', 'testing_count', $count + 4);
	//try to submit a blank record
	$selenium->click('Update');
	$selenium->write('verifyTextNotPresent', 'ago');		
	$selenium->openPage('/' . $name);		
	$selenium->write('verifyTextNotPresent', 'ago');
	$selenium->write('verifyValue', 'testing_count', $count + 5);	
	//try to submit a 141 character message
	$selenium->openPage('/home');		
	$selenium->write('type', 'message', $message_long);	
	$selenium->click('Update');
	$selenium->openPage('/home');
	$selenium->write('verifyTextNotPresent', $message_long);	
	$selenium->openPage('/' . $name);
	$selenium->write('verifyTextNotPresent', $message_long);	
	$selenium->openPage('/public_timeline');
	$selenium->write('verifyTextNotPresent', $message_long);
	$selenium->write('verifyValue', 'testing_count', $count + 5);			
	//post a message
	$selenium->openPage('/home');		
	$selenium->write('type', 'message', $message);
	$selenium->click('Update');
	$selenium->openPage('/public_timeline');
	$selenium->write('verifyValue', 'testing_count', $count + 7);
	$selenium->write('verifyTextNotPresent', $message_long);
	$selenium->write('verifyTextPresent', $message);	
	$selenium->openPage('/' . $name);	
	$selenium->write('verifyTextPresent', $message);
	$selenium->write('verifyTextNotPresent', 'This user has locked their status updates');	
	$selenium->write('verifyTextPresent', 'RSS feed of');
	$selenium->write('verifyElementPresent', 'head_rss');
	$selenium->write('verifyTextPresent', $message);
	//sign out and check that message is present
	$selenium->signOut();
	$selenium->openPage('/' . $name);	
	$selenium->write('verifyTextPresent', $message);
	$selenium->write('verifyTextNotPresent', 'This user has locked their status updates');	
	$selenium->write('verifyTextPresent', 'RSS feed of');
	$selenium->write('verifyElementPresent', 'head_rss');	
	//lock account and post second message
	$selenium->signIn($name, $password);	
	$selenium->openPage('/settings');
	$selenium->write('assertNotChecked', 'locked');		
	$selenium->write('click', 'locked');
	$selenium->click('Update');
	$selenium->write('verifyValue', 'testing_count', $count + 7);	
	$selenium->write('assertChecked', 'locked');
	$selenium->write('verifyTextPresent', 'Your profile was updated.');
	$selenium->openPage('/home');	
	$selenium->write('type', 'message', $message2);
	$selenium->click('Update');
	$selenium->openPage('/public_timeline');
	$selenium->write('verifyValue', 'testing_count', $count + 8);	
	$selenium->write('verifyTextNotPresent', $message2);
	//$selenium->open404('/rss/user/' . $name);	
	//check that message IS visible when signed viewing your own profile
	$selenium->openPage('/' . $name);	
	$selenium->write('verifyTextPresent', $message2);
	$selenium->write('verifyTextNotPresent', 'This user has locked their status updates');	
	$selenium->write('verifyTextNotPresent', 'RSS feed of');
	$selenium->write('verifyElementNotPresent', 'head_rss');
	$selenium->openPage('/home');	
	$selenium->write('verifyTextPresent', $message2);
	//sign out and view as member of public
	$selenium->signOut();
	$selenium->openPage('/' . $name);
	$selenium->write('verifyTextNotPresent', $message2);
	$selenium->write('verifyTextPresent', 'This user has locked their status updates');	
	$selenium->write('verifyTextNotPresent', 'RSS feed of');
	$selenium->write('verifyElementNotPresent', 'head_rss');
	//sign in and unluck in order to make following easier
	$selenium->signIn($name, $password);
	$selenium->openPage('/settings');
	$selenium->write('assertChecked', 'locked');
	$selenium->write('click', 'locked');
	$selenium->click('Update');
	$selenium->write('assertNotChecked', 'locked');
	$selenium->signOut();
	//sign up as second user and follow first
	$selenium->signUp($name2, $password2, $email2);	
	$selenium->signIn($name2, $password2);
	$selenium->openPage('/' . $name);
	$selenium->write('clickAndWait', 'follow');
	$selenium->signOut();
	//sign in as first and change lock again
	$selenium->signIn($name, $password);	
	$selenium->openPage('/settings');
	$selenium->write('assertNotChecked', 'locked');		
	$selenium->write('click', 'locked');
	$selenium->click('Update');
	$selenium->openPage('/home');
	$selenium->write('type', 'message', $message3);
	$selenium->click('Update');
	$selenium->openPage('/home');
	$selenium->write('verifyTextPresent', $message3);
	//sign in as second user and make sure you can see posts
	$selenium->signOut();
	$selenium->signIn($name2, $password2);	
	$selenium->openPage('/' . $name);	
	$selenium->write('verifyTextPresent', $message2);
	$selenium->write('verifyTextNotPresent', 'This user has locked their status updates');	
	$selenium->write('verifyTextNotPresent', 'RSS feed of');
	$selenium->write('verifyElementNotPresent', 'head_rss');
	$selenium->openPage('/admin/flush');
?>