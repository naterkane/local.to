<?php
	$name = 'b';
	$password = 'testing123';
	$email = "nomcat+".$selenium->randomString(10) . '@wearenom.com';
	$too_long_name = $selenium->randomString(16);
	$too_short_password = $selenium->randomString(5);	
	$too_long_password = $selenium->randomString(26);		
	$error = 'There was an error signing up. Please see below for details.';
	$reserved = 'groups';
	$selenium->caseTitle('Sign Me Up');
	$selenium->signOut();
	//sign up
	$selenium->signUp($name, $password, $email);
	$selenium->signIn($name, $password);
	$selenium->openPage('/' . $name);
?>