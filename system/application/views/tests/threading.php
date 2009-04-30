<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);
	$blank = '@' . $name . ' ';	
	$message = $selenium->randomString(10);
	$message2 = $blank . $selenium->randomString(10);	
	$message_long = $selenium->randomString(141);	
	$email = $selenium->randomString(10) . '@' . $selenium->randomString(10) . '.com';		
	$selenium->caseTitle('Add Message');
	$selenium->signOut();	
	$selenium->signUp($name, $password, $email);
	$selenium->signIn($name, $password);
	//post a message
	$selenium->write('type', 'message', $message);
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $message);
	$selenium->write('clickAndWait', 'xpath=//a[text()="[Reply]"]');
	$selenium->write('storeValue', 'reply_to', 'reply_to');
	$selenium->write('storeValue', 'reply_to', 'reply_to_username');
	$selenium->write('verifyValue', 'message', $blank);
	$selenium->write('type', 'message', $message2);	
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $message2);
	$selenium->write('clickAndWait', 'xpath=//a[text()="this message"]');	
	$selenium->openPage('/home');
	$selenium->write('verifyTextPresent', $message);
	$selenium->openPage('/admin/flush');
?>