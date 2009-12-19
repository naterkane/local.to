<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);
	$name2 = $selenium->randomString(10);
	$password2 = $selenium->randomString(10);	
	$message = $selenium->randomString(10);		
	$email = "nomcat+".$selenium->randomString(10) . '@wearenom.com';	
	$email2 = "nomcat+".$selenium->randomString(10) . '@wearenom.com';		
	$count = $this->testingData['count'];		
	$selenium->caseTitle('Message');	
	$selenium->signOut();
	$selenium->write('verifyValue', 'testing_count', $count);	
	$selenium->signUp($name, $password, $email);
	$selenium->signIn($name, $password);
	$selenium->write('verifyValue', 'testing_count', $count + 4);
	//post a message
	$selenium->openPage('/home');		
	$selenium->write('type', 'message', $message);
	$selenium->click('Update');
	$selenium->openPage('/home');
	$selenium->write('verifyTextPresent', $message);
	$selenium->write('verifyElementPresent', 'delete_1');
	$selenium->write('verifyElementPresent', 'reply_link_1');
	$selenium->write('verifyElementPresent', 'favorite_link_1');	
	$selenium->openPage('/' . $name . '/status/1');	
	$selenium->write('verifyTextPresent', $message);
	$selenium->write('verifyElementPresent', 'delete_1');
	$selenium->write('verifyElementPresent', 'reply_link_1');
	$selenium->write('verifyElementPresent', 'favorite_link_1');		
	//sign out and make sure delete link is not present
	$selenium->signOut();
	$selenium->openPage('/' . $name);
	$selenium->write('verifyTextPresent', $message);
	$selenium->write('verifyElementNotPresent', 'delete-1');
	$selenium->write('verifyElementNotPresent', 'reply_link_1');
	$selenium->write('verifyElementNotPresent', 'favorite_link_1');	
	$selenium->openPage('/' . $name . '/status/1');	
	$selenium->write('verifyTextPresent', $message);
	$selenium->write('verifyElementNotPresent', 'delete-1');
	$selenium->write('verifyElementNotPresent', 'reply_link_1');	
	$selenium->write('verifyElementNotPresent', 'favorite_link_1');		
	//make second account and attempt to delete first link
	$selenium->signUp($name2, $password2, $email2);
	$selenium->signIn($name2, $password2);	
	$selenium->openPage('/messages/delete/1');
	$selenium->write('verifyTextPresent', 'There was an error deleting the message');	
	$selenium->openPage('/' . $name);
	$selenium->write('verifyTextPresent', $message);
	$selenium->write('verifyElementNotPresent', 'delete_1');
	$selenium->write('verifyElementPresent', 'reply_link_1');	
	$selenium->write('verifyElementPresent', 'favorite_link_1');		
	$selenium->openPage('/' . $name . '/status/1');	
	$selenium->write('verifyTextPresent', $message);
	$selenium->write('verifyElementNotPresent', 'delete_1');
	$selenium->write('verifyElementPresent', 'reply_link_1');		
	$selenium->write('verifyElementPresent', 'favorite_link_1');
	//sign in to the first account and delete the message
	$selenium->signOut();
	$selenium->signIn($name, $password);
	$selenium->write('clickAndWait', 'delete_1');
	$selenium->write('assertConfirmation', 'Are your sure you want to delete this message? This can not be undone.');	
	$selenium->checkErrors();
	$selenium->write('verifyTextPresent', 'The message was deleted');	
	$selenium->write('verifyTextPresent', 'User has deleted this post');
	$selenium->write('verifyElementNotPresent', 'delete_1');
	$selenium->write('verifyElementNotPresent', 'reply_link_1');
	$selenium->write('verifyElementNotPresent', 'favorite_link_1');	
	$selenium->openPage('/' . $name . '/status/1');	
	$selenium->write('verifyTextPresent', 'User has deleted this post');		
	$selenium->write('verifyTextNotPresent', $message);
	$selenium->write('verifyElementNotPresent', 'delete_1');
	$selenium->write('verifyElementNotPresent', 'reply_link_1');	
	$selenium->write('verifyElementNotPresent', 'favorite_link_1');	
	//sign in to second account and favorite
	$selenium->signOut();
	$selenium->signIn($name2, $password2);		
	$selenium->openPage('/messages/favorite/1');	
	$selenium->write('verifyTextPresent', 'There was an error adding the message to your favorites');	
	$selenium->openPage('/admin/flush');
?>