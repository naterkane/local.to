<?php
	$name = $this->selenium->randomString(10);
	$password = $this->selenium->randomString(10);	
	$name2 = $this->selenium->randomString(10);
	$password2 = $this->selenium->randomString(10);	
	$group = $this->selenium->randomString(10);
	$this->selenium->caseTitle('Follow');
	//create first account and sign out
	$this->selenium->openPage('/admin/flush');
	$this->selenium->signOut();	
	$this->selenium->signUp($name, $password);
	$this->selenium->signIn($name, $password);
	//create a group
	$this->selenium->openPage('/groups/add');
	$this->selenium->write('type', 'name', $group);
	$this->selenium->click('Sign Up');
	$this->selenium->write('verifyTextPresent', $group);
	$this->selenium->write('verifyTextPresent', $name);
	$this->selenium->write('verifyTextPresent', 'Unsubscribe');	
	//create second account	
	$this->selenium->signOut();	
	$this->selenium->signUp($name2, $password2);
	$this->selenium->signIn($name2, $password2);
	//subscribe to group
	$this->selenium->openPage('/group/' . $group);	
	$this->selenium->write('verifyTextPresent', $group);
	$this->selenium->write('verifyTextPresent', $name);
	$this->selenium->write('verifyTextPresent', 'Subscribe');	
	$this->selenium->write('openAndWait', '/groups/subscribe/' . $group);
	$this->selenium->write('verifyTextPresent', $group);
	$this->selenium->write('verifyTextPresent', $name);
	$this->selenium->write('verifyTextPresent', $name2);	
	$this->selenium->write('verifyTextPresent', 'Unsubscribe');	
	$this->selenium->write('openAndWait', '/groups/unsubscribe/' . $group);
	$this->selenium->write('verifyTextPresent', $group);
	$this->selenium->write('verifyTextPresent', $name);
	$this->selenium->write('verifyTextNotPresent', $name2);	
	$this->selenium->write('verifyTextPresent', 'Subscribe');
	$this->selenium->openPage('/admin/flush');	
?>