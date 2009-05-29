<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);	
	$email = "nomcat+".$selenium->randomString(10) . '@wearenom.com';
	$name2 = $selenium->randomString(10);
	$password2 = $selenium->randomString(10);	
	$name3 = $selenium->randomString(10);
	$password3 = $selenium->randomString(10);	
	$email2 = "nomcat+".$selenium->randomString(10) . '@wearenom.com';
	$email3 = "nomcat+".$selenium->randomString(10) . '@wearenom.com';
	$email4 = "nomcat+".$selenium->randomString(10) . '@wearenom.com';
	$email5 = "nomcat+".$selenium->randomString(10) . '@wearenom.com';			
	$group = $selenium->randomString(10);
	$count = $this->testingData['count'];		
	$error = 'There was an error adding your group. Please see below for details.';
	$selenium->caseTitle('Add Group and And Members');
	//sign up a superuser
	$selenium->signOut();	
	$selenium->write('verifyValue', 'testing_count', $count);	
	$selenium->signUp($name, $password, $email);
	$selenium->write('verifyValue', 'testing_count', $count + 4);
	$selenium->signIn($name, $password);
	//add a group
	$selenium->openPage('/groups/add');
	$selenium->write('type', 'name', $group);
	$selenium->click('Add');
	$selenium->write('verifyTextPresent', $group);
	$selenium->write('verifyTextPresent', 'Members (1)');
	$selenium->write('verifyValue', 'testing_count', $count + 8);
	//post empty invite form
	$selenium->openPage('/groups/invites/' . $group);	
	$selenium->write('verifyElementNotPresent', 'invites_table');		
	$selenium->click('Add Invites');	
	$selenium->write('verifyTextPresent', 'You must provide an email address.');	
	$selenium->write('verifyElementNotPresent', 'invites_table');		
	$selenium->write('verifyValue', 'testing_count', $count + 8);
	//post commas
	$selenium->openPage('/groups/invites/' . $group);	
	$selenium->write('type', 'invites', ',,,,,');	
	$selenium->click('Add Invites');
	$selenium->write('verifyTextPresent', 'You must provide an email address.');	
	$selenium->write('verifyElementNotPresent', 'invites_table');
	$selenium->write('verifyValue', 'testing_count', $count + 8);
	//post one bad email addresse
	$selenium->write('type', 'invites', 'lkdsjdslkf');
	$selenium->click('Add Invites');	
	$selenium->write('verifyTextPresent', '1 email(s) not added. Emails not added: lkdsjdslkf.');
	$selenium->write('verifyElementNotPresent', 'invites_table');
	$selenium->write('verifyValue', 'testing_count', $count + 8);
	//post many bad addresses
	$selenium->write('type', 'invites', 'djhfkdfsh, kdjfhkdsjf, dfkdsjfhds');
	$selenium->click('Add Invites');	
	$selenium->write('verifyTextPresent', '3 email(s) not added. Emails not added: djhfkdfsh, kdjfhkdsjf, dfkdsjfhds.');	
	$selenium->write('verifyElementNotPresent', 'invites_table');
	$selenium->write('verifyValue', 'testing_count', $count + 8);
	//post one good address
	$selenium->write('type', 'invites', $email3);
	$selenium->click('Add Invites');	
	$selenium->write('verifyTextPresent', '1 invites(s) added.');	
	$selenium->write('verifyElementPresent', 'count-0');
	$selenium->write('verifyElementNotPresent', 'count-1');	
	$selenium->write('storeValue', 'count-0', 'key1');	
	$selenium->write('verifyValue', 'testing_count', $count + 9);
	//mix good and bad addresses
	$selenium->write('type', 'invites', 'dskfjhd, ' . $email4 . ', ' . $email5 . ', dfhkdhfsd');
	$selenium->click('Add Invites');
	$selenium->write('verifyTextPresent', '2 invites(s) added. 2 email(s) not added. Emails not added: dskfjhd, dfhkdhfsd.');
	$selenium->write('verifyElementPresent', 'count-0');
	$selenium->write('verifyElementPresent', 'count-1');
	$selenium->write('verifyElementPresent', 'count-2');	
	$selenium->write('verifyValue', 'testing_count', $count + 11);
	$selenium->write('storeValue', 'count-1', 'key2');
	//delete one invite
	$selenium->write('clickAndWait', 'xpath=id(\'invites_table\')/tbody/tr[2]/td[4]/a');
	$selenium->write('assertConfirmation', 'Are you sure you want to delete this invitation? This cannot be undone.');	
	$selenium->checkErrors();
	$selenium->write('verifyTextPresent', 'Invitation successfully deleted.');
	$selenium->write('verifyValue', 'testing_count', $count + 10);	
	$selenium->write('verifyElementPresent', 'count-0');
	$selenium->write('verifyElementPresent', 'count-1');
	$selenium->write('verifyElementNotPresent', 'count-2');
	$selenium->write('verifyTextNotPresent', $email5);	
	$selenium->write('verifyTextPresent', $email3);
	$selenium->write('verifyTextNotPresent', $email2);
	$selenium->write('verifyTextNotPresent', $email);	
	//delete a non-existing key
	$selenium->openPage('/groups/deleteinvite/123');
	$selenium->write('verifyValue', 'testing_count', $count + 10);	
	$selenium->write('verifyTextPresent', 'The invitation could not be delete.');
	//sign up as a non resitered user
	$selenium->signout();
	$selenium->openPage('/groups/accept/${key1}');
	$selenium->write('type', 'username', $name2);
	$selenium->write('type', 'password', $password2);
	$selenium->write('type', 'passwordconfirm', $password2);	
	$selenium->write('type', 'email', $email2);
	$selenium->click('Sign Up');
	$selenium->write('verifyValue', 'testing_count', $count + 12);	
	$selenium->write('type', 'username', $name2);
	$selenium->write('type', 'password', $password2);
	$selenium->click('Sign In');	
	$selenium->write('verifyValue', 'testing_count', $count + 12);
	$selenium->write('verifyTextPresent', 'Members (2)');
	//sign up as a pre-existing user
	$selenium->signOut();	
	$selenium->signUp($name3, $password3, $email3);
	$selenium->signIn($name3, $password3);
	$selenium->openPage('/groups/accept/${key2}');
	$selenium->write('verifyTextPresent', 'Welcome to ' . $group);
	//try accessing invites
	$selenium->write('openAndWait', '/groups/invites/' . $group);
	$selenium->write('verifyTextPresent', $selenium->missingText);
	$selenium->openPage('/admin/flush');
?>