<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);
	$message = $selenium->randomString(10);
	$message2 = $selenium->randomString(10);	
	$message_long = $selenium->randomString(141);	
	$email = $selenium->randomString(10) . '@' . $selenium->randomString(10) . '.com';		
	$selenium->caseTitle('Add Message');
	$selenium->signOut();	
	$selenium->signUp($name, $password, $email);
	$selenium->signIn($name, $password);
	//try to submit a blank record
	$selenium->click('Update');
	$selenium->write('verifyTextNotPresent', 'ago');		
	$selenium->openPage('/' . $name);		
	$selenium->write('verifyTextNotPresent', 'ago');		
	//try to submit a 141 character message
	$selenium->openPage('/home');		
	$selenium->write('type', 'message', $message_long);	
	$selenium->click('Update');
	$selenium->write('verifyTextNotPresent', $message_long);	
	$selenium->openPage('/' . $name);			
	$selenium->write('verifyTextNotPresent', $message_long);	
	$selenium->openPage('/public_timeline');	
	$selenium->write('verifyTextNotPresent', $message_long);		
	//post a message
	$selenium->write('type', 'message', $message);
	$selenium->click('Update');
	$selenium->openPage('/public_timeline');	
	$selenium->write('verifyTextNotPresent', $message_long);
	$selenium->openPage('/' . $name);	
	$selenium->write('verifyTextPresent', $message);
	$selenium->openPage('/home');
	$selenium->write('verifyTextPresent', $message);
	//post second message to timeline by unlocking
	$selenium->openPage('/settings');
	$selenium->write('assertChecked', 'locked');		
	$selenium->write('click', 'locked');	
	$selenium->click('Update');
	$selenium->write('assertNotChecked', 'locked');	
	$selenium->write('verifyTextPresent', 'Your profile was updated.');
	$selenium->openPage('/home');	
	$selenium->write('type', 'message', $message2);
	$selenium->click('Update');
	$selenium->openPage('/public_timeline');	
	$selenium->write('verifyTextPresent', $message2);
	$selenium->openPage('/' . $name);	
	$selenium->write('verifyTextPresent', $message2);
	$selenium->openPage('/home');
	$selenium->write('verifyTextPresent', $message2);	
	$selenium->openPage('/admin/flush');
?>