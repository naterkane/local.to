<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);
	$email = "nomcat+".$selenium->randomString(10) . '@wearenom.com';	
	$count = $this->testingData['count'];		
	$selenium->caseTitle('DM');	
	$selenium->signOut();	
	$selenium->write('verifyValue', 'testing_count', $count);	
	$selenium->signUp($name, $password, $email);
	$selenium->signIn($name, $password);
	$selenium->write('verifyValue', 'testing_count', $count + 4);
	//submit 40 mesages
	for ($i=20; $i < 60; $i++) { 
		$selenium->write('openAndWait', '/home');		
		$selenium->write('type', 'message', $i);
		$selenium->write('clickAndWait', '//input[@value=\'Update\']');		
	}
	$selenium->write('verifyValue', 'testing_count', $count + 46);
	$selenium->write('verifyTextPresent', page::$nextText);	
	for ($i=20; $i < 40; $i++) { 
		$selenium->write('verifyTextNotPresent', $i);		
	}	
	for ($i=40; $i < 60; $i++) { 
		$selenium->write('verifyTextPresent', $i);		
	}
	$selenium->openPage('/home/page/2');
	$selenium->write('verifyTextNotPresent', page::$nextText);	
	for ($i=20; $i < 40; $i++) { 
		$selenium->write('verifyTextPresent', $i);		
	}
	for ($i=40; $i < 60; $i++) { 
		$selenium->write('verifyTextNotPresent', $i);		
	}
	$selenium->openPage('/public_timeline');	
	$selenium->write('verifyTextPresent', page::$nextText);	
	for ($i=20; $i < 40; $i++) { 
		$selenium->write('verifyTextNotPresent', $i);		
	}	
	for ($i=40; $i < 60; $i++) { 
		$selenium->write('verifyTextPresent', $i);		
	}
	$selenium->openPage('/public_timeline/page/2');
	$selenium->write('verifyTextNotPresent', page::$nextText);	
	for ($i=20; $i < 40; $i++) { 
		$selenium->write('verifyTextPresent', $i);		
	}
	for ($i=40; $i < 60; $i++) { 
		$selenium->write('verifyTextNotPresent', $i);		
	}
	$selenium->openPage('/admin/flush');
?>