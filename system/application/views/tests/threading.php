<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);
	$blank = '@' . $name . ' ';	
	$message = $selenium->randomString(10);
	$message2 = $blank . $selenium->randomString(10);
	$message3 = $blank . $selenium->randomString(10);
	$message4 = $blank . $selenium->randomString(10);		
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
	//try to post to a reply to a non-existant username and fail miserably
	$selenium->write('clickAndWait', 'xpath=//a[text()="[Reply]"]');	
	$selenium->write('type', 'message', $message3);	
	$selenium->write('type', 'reply_to_username', 'blah');		
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', 'There was an error adding your message.');
	$selenium->write('verifyTextNotPresent', $message3);
	//try to post to a reply to a non-existant user_id and fail miserably
	$selenium->write('clickAndWait', 'xpath=//a[text()="[Reply]"]');	
	$selenium->write('type', 'message', $message3);	
	$selenium->write('type', 'reply_to', 'blah');		
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', 'There was an error adding your message.');
	$selenium->write('verifyTextNotPresent', $message3);	
	//test threading view
	$selenium->openPage('/settings');
	$selenium->write('assertChecked', 'locked');		
	$selenium->write('click', 'locked');	
	$selenium->click('Update');
	$selenium->write('assertNotChecked', 'locked');	
	$selenium->write('verifyTextPresent', 'Your profile was updated.');
	$selenium->openPage('/home');	
	$selenium->write('type', 'message', $message3);		
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $message3);
	$selenium->openPage('/public_timeline');	
	$selenium->write('verifyTextPresent', $message3);
	$selenium->openPage('/public_timeline_threaded');		
	$selenium->write('verifyTextPresent', $message3);
	$selenium->write('clickAndWait', 'xpath=//a[text()="[Reply]"]');	
	$selenium->write('type', 'message', $message4);		
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $message4);
	$selenium->openPage('/public_timeline');	
	$selenium->write('verifyTextPresent', $message4);
	$selenium->openPage('/public_timeline_threaded');		
	$selenium->write('verifyTextNotPresent', $message4);	
	$selenium->openPage('/admin/flush');
?>