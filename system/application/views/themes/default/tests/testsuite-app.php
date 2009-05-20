<?php
	$selenium->suiteTitle('Microblog Tests (FLUSH YOUR DATABASE BEFORE USING)');
	$selenium->addTestCase('Sign Up', 'sign-up');
	$selenium->addTestCase('Change Settings', 'settings');	
	$selenium->addTestCase('Message', 'message');
	$selenium->addTestCase('Direct Message', 'dm');		
	$selenium->addTestCase('Threading', 'threading');	
	$selenium->addTestCase('Follow', 'follow');
	$selenium->addTestCase('Add Group and Subscribe', 'group');
	$selenium->addTestCase('Add Group and Change Profile', 'group-settings');	
	$selenium->addTestCase('Access', 'access');	
	$selenium->addTestCase('Disallowed Characters', 'characters');		
	$selenium->addTestCase('Create user A (Not Really a Test, Just Tired of Making Stupid Accounts)', 'sign-me-up');
	$selenium->addTestCase('Create user B (Not Really a Test, Just Tired of Making Stupid Accounts)', 'sign-me-up2');	
?>