<?php
	$name = $this->selenium->randomString(10);
	$password = $this->selenium->randomString(10);	
	$email = $this->selenium->randomString(10) . '@' . $this->selenium->randomString(10) . '.com';	
	$name2 = $this->selenium->randomString(10);
	$password2 = $this->selenium->randomString(10);	
	$email2 = $this->selenium->randomString(10) . '@' . $this->selenium->randomString(10) . '.com';	
	$message = $this->selenium->randomString(10);
	$message2 = $this->selenium->randomString(10);				
	$this->selenium->caseTitle('Follow');
	//create first account and sign out
	$this->selenium->signOut();	
	$this->selenium->signUp($name, $password, $email);
	$this->selenium->signIn($name, $password);
	$this->selenium->signOut();	
	//create second account	
	$this->selenium->signOut();	
	$this->selenium->signUp($name2, $password2, $email2);
	$this->selenium->signIn($name2, $password2);
	//check follower counts
	$this->selenium->openPage('/home');
	$this->selenium->write('verifyTextPresent', 'Following: 0 Followers: 0');
	//follow first account
	$this->selenium->openPage('/' . $name);		
	$this->selenium->write('clickAndWait', 'follow');
	$this->selenium->write('verifyTextPresent', 'A confirmation request has been sent to ' . $name . ' for confirmation.');
	$this->selenium->write('verifyTextPresent', 'Pending a friend request');	
	//try to follow a second time and get error
	$this->selenium->write('openAndWait', '/follow/' . $name);	
	$this->selenium->write('verifyTextPresent', $this->selenium->missingText);	
	//try to follow yourself
	$this->selenium->write('openAndWait', '/follow/' . $name2);	
	$this->selenium->write('verifyTextPresent', $this->selenium->missingText);	
	$this->selenium->signOut();		
	//go to first user's home page
	$this->selenium->signIn($name, $password);	
	$this->selenium->openPage('/home');	
	$this->selenium->write('verifyTextPresent', 'Following: 0 Followers: 0');
	//accept friend request
	$this->selenium->openPage('/friend_requests');	
	$this->selenium->write('verifyTextPresent', $name2);
	$this->selenium->write('clickAndWait', 'confirm' . $name2);	
	$this->selenium->write('verifyTextPresent', $name2 . ' is now following your posts.');
	$this->selenium->write('verifyTextPresent', 'Following: 0 Followers: 1');
	//try and accept a friend request from someone who did not request
	$this->selenium->write('openAndWait', '/confirm/1');		
	$this->selenium->write('verifyTextPresent', 'Following: 0 Followers: 1');	
	$this->selenium->write('verifyTextPresent', 'There was problem adding this follower');	
	//post a message
	$this->selenium->write('type', 'message', $message);
	$this->selenium->click('Update');
	$this->selenium->openPage('/');
	$this->selenium->write('verifyTextPresent', $message);
	$this->selenium->signOut();		
	//make sure second account sees post and is following
	$this->selenium->signIn($name2, $password2);
	$this->selenium->openPage('/home');	
	$this->selenium->write('verifyTextPresent', $message);
	$this->selenium->write('verifyTextPresent', 'Following: 1 Followers: 0');	
	$this->selenium->openPage('/' . $name2);	
	$this->selenium->write('verifyTextNotPresent', $message);	
	//unsubscribe
	$this->selenium->openPage('/' . $name);		
	$this->selenium->write('clickAndWait', 'unfollow');
	$this->selenium->write('verifyTextPresent', 'Follow');
	$this->selenium->openPage('/home');	
	$this->selenium->write('verifyTextPresent', 'Following: 0 Followers: 0');	
	$this->selenium->signOut();	
	//Post again as first
	$this->selenium->signIn($name, $password);
	$this->selenium->openPage('/home');	
	$this->selenium->write('verifyTextPresent', 'Following: 0 Followers: 0');	
	$this->selenium->write('type', 'message', $message2);
	$this->selenium->click('Update');
	$this->selenium->openPage('/');
	$this->selenium->write('verifyTextPresent', $message2);
	$this->selenium->signOut();	
	//make sure second message is not there
	$this->selenium->signIn($name2, $password2);
	$this->selenium->openPage('/home');	
	$this->selenium->write('verifyTextNotPresent', $message2);
	$this->selenium->openPage('/' . $name2);	
	$this->selenium->write('verifyTextNotPresent', $message2);	
	$this->selenium->openPage('/admin/flush');	
?>