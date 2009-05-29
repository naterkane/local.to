<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);
	$email = "nomcat+".$selenium->randomString(10) . '@wearenom.com';
	$selenium->caseTitle('Cookie Security');
	$selenium->signOut();		
	//create an invite and store
	$selenium->signup($name, $password, $email);
	$selenium->signin($name, $password);
	$selenium->openPage('/home');	
	//change user_agent
	$selenium->openPage('/admin/cookie/user_agent');
	$selenium->openPage('/home');
	$selenium->write('verifyLocation', $this->config->item('base_url') . 'signin?redirect=%2Fhome');	
	//change ip
	$selenium->openPage('/admin/cookie/ip');
	$selenium->openPage('/home');
	$selenium->write('verifyLocation', $this->config->item('base_url') . 'signin?redirect=%2Fhome');
	$selenium->openPage('/admin/flush');	
?>