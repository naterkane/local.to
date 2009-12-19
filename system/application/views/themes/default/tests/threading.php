<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);
	$blank = '@' . $name . ' ';	
	$message = $selenium->randomString(10);
	$message2 = $blank . $selenium->randomString(10);
	$message3 = $blank . $selenium->randomString(10);
	$message4 = $blank . $selenium->randomString(10);		
	$message_long = $selenium->randomString(141);	
	$email = "nomcat+".$selenium->randomString(10) . '@wearenom.com';	
	$selenium->caseTitle('Add Message');
	$selenium->signOut();	
	$selenium->signUp($name, $password, $email);
	$selenium->signIn($name, $password);
	//post a two messages
	$selenium->write('type', 'message', $message);
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $message);
	$selenium->write('type', 'message', $message2);
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $message2);
	$selenium->write('verifyOrdered', 'status-message-2', 'status-message-1');	
	//$selenium->write('clickAndWait', 'xpath=//a[text()="'.$name.'\'s message"]');	
	$selenium->openPage('/home');
	//try to post to a reply to a non-existant username and fail miserably
	$selenium->write('clickAndWait', 'xpath=//a[text()="Reply"]');	
	$selenium->write('type', 'message', $message3);	
	$selenium->write('type', 'reply_to_username', 'blah');
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', 'There was an error adding your message.');
	$selenium->openPage('/home');
	$selenium->write('verifyTextNotPresent', $message3);
	//try to post to a reply to a non-existant user_id and fail miserably
	$selenium->write('clickAndWait', 'reply_link_1');	
	$selenium->write('type', 'message', $message3);	
	$selenium->write('type', 'reply_to', 'blah');		
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', 'There was an error adding your message.');
	$selenium->openPage('/home');	
	$selenium->write('verifyTextNotPresent', $message3);
	//add a reply
	$selenium->openPage('/home');
	$selenium->write('clickAndWait', 'reply_link_1');
	$selenium->write('type', 'message', $message3);		
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $message3);
	$selenium->write('verifyOrdered', 'status-message-2', 'status-message-1');
	$selenium->write('verifyOrdered', 'status-message-1', 'status-message-5');
	//check message is on timeline, threaded and unthreaded
	$selenium->openPage('/public_timeline');
	$selenium->write('verifyOrdered', 'status-message-2', 'status-message-1');
	$selenium->write('verifyOrdered', 'status-message-1', 'status-message-5');
	$selenium->write('clickAndWait', 'toggleOff');		
	$selenium->write('verifyOrdered', 'status-message-5', 'status-message-2');
	$selenium->write('verifyOrdered', 'status-message-2', 'status-message-1');
	$selenium->openPage('/admin/flush');	
?>