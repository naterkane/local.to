<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);	
	$email = "nomcat+".$selenium->randomString(10) . '@wearenom.com';	
	$name2 = $selenium->randomString(10);
	$password2 = $selenium->randomString(10);	
	$email2 = "nomcat+".$selenium->randomString(10) . '@wearenom.com';
	$message = $selenium->randomString(10);
	$message2 = $selenium->randomString(10);	
	$message3 = $selenium->randomString(10);					
	$count = $this->testingData['count'];	
	$selenium->caseTitle('Follow');	
	//create first account, post, and sign out
	$selenium->signOut();	
	$selenium->write('verifyValue', 'testing_count', $count);	
	$selenium->signUp($name, $password, $email);
	$selenium->write('verifyValue', 'testing_count', $count + 4);	
	$selenium->signIn($name, $password);
	$selenium->openPage('/home');		
	$selenium->write('type', 'message', $message);
	$selenium->click('Update');	
	$selenium->openPage('/home');
	$selenium->write('verifyTextPresent', $message);	
	$selenium->write('verifyValue', 'testing_count', $count + 7);	
	$selenium->signOut();
	//create second account	and post two messages
	$selenium->signOut();	
	$selenium->signUp($name2, $password2, $email2);
	$selenium->write('verifyValue', 'testing_count', $count + 10);
	$selenium->signIn($name2, $password2);
	$selenium->write('type', 'message', $message2);
	$selenium->click('Update');	
	$selenium->openPage('/home');
	$selenium->write('verifyValue', 'testing_count', $count + 11);	
	$selenium->write('verifyTextPresent', $message2);
	$selenium->write('type', 'message', $message3);
	$selenium->click('Update');	
	$selenium->openPage('/home');
	$selenium->write('verifyValue', 'testing_count', $count + 12);	
	$selenium->write('verifyTextPresent', $message3);			
	//follow first account
	$selenium->openPage('/' . $name);
	$selenium->write('clickAndWait', 'follow');
	$selenium->write('verifyValue', 'testing_count', $count + 12);
	$selenium->write('verifyTextPresent', 'You are now following ' . $name);
	$selenium->openPage('/home');
	$selenium->write('verifyTextPresent', $message);
	$selenium->write('verifyTextPresent', $message2);
	$selenium->write('verifyTextPresent', $message3);		
	$selenium->write('verifyOrdered', 'id=status-message-3', 'id=status-message-2', 'id=status-message-1');	
	$selenium->openPage('/admin/flush');	
?>