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
	for ($i=1; $i < 41; $i++) { 
		$selenium->write('openAndWait', '/home');		
		$selenium->write('type', 'message', $i);
		$selenium->write('clickAndWait', '//input[@value=\'Update\']');	
		$selenium->write('verifyTextPresent', $i);		
	}
	$selenium->write('verifyValue', 'testing_count', $count + 46);
	$selenium->write('verifyTextPresent', Page::$nextText);	
	$selenium->write('verifyTextNotPresent', Page::$previousText);
	for ($i=1; $i < 21; $i++) { 
		$selenium->write('verifyElementNotPresent', 'message-text-' . $i);
	}	
	for ($i=21; $i < 41; $i++) { 
		$selenium->write('verifyElementPresent', 'message-text-' . $i);
	}
	$selenium->openPage('/home/page/2');
	$selenium->write('verifyTextNotPresent', Page::$nextText);
	$selenium->write('verifyTextPresent', Page::$previousText);
	for ($i=1; $i < 21; $i++) { 
		$selenium->write('verifyElementPresent', 'message-text-' . $i);
	}
	for ($i=21; $i < 41; $i++) { 
		$selenium->write('verifyElementNotPresent', 'message-text-' . $i);
	}
	$selenium->openPage('/public_timeline');	
	$selenium->write('verifyTextPresent', Page::$nextText);	
	$selenium->write('verifyTextNotPresent', Page::$previousText);	
	for ($i=1; $i < 21; $i++) { 
		$selenium->write('verifyElementNotPresent', 'message-text-' . $i);
	}	
	for ($i=21; $i < 41; $i++) { 
		$selenium->write('verifyElementPresent', 'message-text-' . $i);
	}
	$selenium->openPage('/public_timeline/page/2');
	$selenium->write('verifyTextNotPresent', Page::$nextText);
	$selenium->write('verifyTextPresent', Page::$previousText);	
	for ($i=1; $i < 21; $i++) { 
		$selenium->write('verifyElementPresent', 'message-text-' . $i);
	}
	for ($i=21; $i < 41; $i++) { 
		$selenium->write('verifyElementNotPresent', 'message-text-' . $i);
	}
	$selenium->write('openAndWait', '/public_timeline/page/3');
	$selenium->write('verifyTextPresent', $selenium->missingText);		
	$selenium->write('verifyTextNotPresent', Page::$nextText);
	$selenium->write('verifyTextNotPresent', Page::$previousText);		
	$selenium->openPage('/admin/flush');
?>