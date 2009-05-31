<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);
	$js = htmlentities('<script>alert(\'hi!\')</script>');
	$sql = '\\\'\'; DROP TABLE users;';
	$h = htmlentities('<b></b><i></i><div></div><span></span><em></em>');	
	$email = "nomcat+".$selenium->randomString(10) . '@wearenom.com';
	$count = $this->testingData['count'];
	$selenium->caseTitle('Sanitize');
	$selenium->signOut();	
	$selenium->write('verifyValue', 'testing_count', $count);	
	$selenium->signUp($name, $password, $email);
	$selenium->signIn($name, $password);
	$selenium->write('verifyValue', 'testing_count', $count + 4);
	//try to submit js
	$selenium->write('type', 'message', $js);	
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $js);
	//try to submit sql
	$selenium->write('type', 'message', $sql);
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $sql);
	//try to submit html
	$selenium->write('type', 'message', $h);
	$selenium->click('Update');
	$selenium->write('verifyTextPresent', $h);	
	$selenium->openPage('/admin/flush');
?>