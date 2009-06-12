<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);	
	$email = "nomcat+".$selenium->randomString(10) . '@wearenom.com';	
	$count = $this->testingData['count'];		
	$name_new = $selenium->randomString(10);
	$long_realname = $selenium->randomString(26);	
	$email_new = "nomcat+".$selenium->randomString(10) . '@wearenom.com';	
	$default_time_zone = 'US/Eastern';
	$time_zone = '(GMT+09:00) Tokyo';
	$time_zone_value = 'Asia/Tokyo';	
	$bio = $selenium->randomString(10);
	$url = $selenium->randomString(10);
	$location = $selenium->randomString(10);	
	$real_name = $selenium->randomString(10);	
	$reserved = 'groups';	
	$too_long_name = $selenium->randomString(16);
	$too_long_bio = $selenium->randomString(161);	
	$error = 'There was an error updating your profile. See below for more details.';	
	$selenium->caseTitle('Change Profile');
	//create account and sign in
	$selenium->signOut();
	$selenium->write('verifyValue', 'testing_count', $count);		
	$selenium->signUp($name, $password, $email);
	$selenium->write('verifyValue', 'testing_count', $count + 4);	
	$selenium->signIn($name, $password);
	//go to settings	
	$selenium->openPage('/settings');
	$selenium->write('verifyValue', 'testing_count', $count + 4);		
	$selenium->write('verifyValue', 'username', $name);
	$selenium->write('verifyValue', 'email', $email);
	$selenium->write('verifyValue', 'time_zone', $default_time_zone);	
	//test empty record
	$selenium->write('type', 'username', '');
	$selenium->write('type', 'email', '');	
	$selenium->click('Update');
	$selenium->write('verifyValue', 'testing_count', $count + 4);		
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A username is required');
	$selenium->write('verifyTextPresent', 'A valid email is required');
	//try invalid email
	$selenium->write('type', 'email', $name);	
	$selenium->click('Update');	
	$selenium->write('verifyValue', 'testing_count', $count + 4);		
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A valid email is required');
	//try reserved username
	$selenium->write('type', 'username', $reserved);
	$selenium->click('Update');
	$selenium->write('verifyValue', 'testing_count', $count + 4);		
	$selenium->write('verifyTextPresent', $error);	
	$selenium->write('verifyTextPresent', 'This is a reserved username');
	//too long name
	$selenium->write('type', 'realname', $long_realname);
	$selenium->click('Update');
	$selenium->write('verifyValue', 'testing_count', $count + 4);		
	$selenium->write('verifyTextPresent', $error);	
	$selenium->write('verifyTextPresent', 'A name must be fewer than 25 characters');		
	//too long username
	$selenium->write('type', 'username', $too_long_name);
	$selenium->click('Update');
	$selenium->write('verifyValue', 'testing_count', $count + 4);		
	$selenium->write('verifyTextPresent', $error);	
	$selenium->write('verifyTextPresent', 'A username must be between 1 and 15 characters long');	
	//bad characters in username
	$selenium->write('type', 'username', $name . '!');
	$selenium->click('Update');
	$selenium->write('verifyValue', 'testing_count', $count + 4);		
	$selenium->write('verifyTextPresent', $error);
	$selenium->write('verifyTextPresent', 'A username may only be made up of numbers, letters, and underscores');	
	//too long bio
	$selenium->write('type', 'bio', $too_long_bio);
	$selenium->click('Update');
	$selenium->write('verifyValue', 'testing_count', $count + 4);		
	$selenium->write('verifyTextPresent', $error);	
	$selenium->write('verifyTextPresent', 'Bio must be fewer than 160 characters');	
	//update record
	$selenium->write('type', 'username', $name_new);
	$selenium->write('type', 'email', $email_new);	
	$selenium->write('type', 'bio', $bio);		
	$selenium->write('type', 'realname', $real_name);
	$selenium->write('type', 'location', $location);	
	$selenium->write('type', 'url', $url);
	$selenium->write('select', 'time_zone', $time_zone);	
	$selenium->click('Update');
	$selenium->write('verifyValue', 'testing_count', $count + 4);		
	$selenium->write('verifyTextPresent', 'Your profile was updated.');
	$selenium->openPage('/settings');	
	$selenium->write('verifyValue', 'testing_count', $count + 4);		
	$selenium->write('verifyValue', 'username', $name_new);
	$selenium->write('verifyValue', 'email', $email_new);	
	$selenium->write('verifyValue', 'bio', $bio);		
	$selenium->write('verifyValue', 'realname', $real_name);
	$selenium->write('verifyValue', 'location', $location);	
	$selenium->write('verifyValue', 'url', $url);
	$selenium->write('verifyValue', 'time_zone', $time_zone_value);		
	$selenium->openPage('/admin/flush');	
?>