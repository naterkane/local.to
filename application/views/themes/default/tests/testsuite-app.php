<?php
	$selenium->suiteTitle('Microblog Tests (FLUSH YOUR DATABASE BEFORE USING)');
	$selenium->addTestCase('Sign Up', 'sign-up');
	$selenium->addTestCase('Change Settings', 'settings');	
	$selenium->addTestCase('Add Message and Test Locking', 'message');
	$selenium->addTestCase('Delete Message', 'message-delete');	
	$selenium->addTestCase('Pagination', 'pagination');	
	$selenium->addTestCase('Direct Message', 'dm');
	$selenium->addTestCase('Direct Message Select Form', 'dm-select');	
	$selenium->addTestCase('Threading', 'threading');	
	$selenium->addTestCase('Follow', 'follow');
	$selenium->addTestCase('Follow and message add', 'follow-messages');	
	$selenium->addTestCase('Add Group and Message', 'group');
	$selenium->addTestCase('Add Group and DM', 'group-dm');	
	$selenium->addTestCase('Add Group and Change Profile', 'group-settings');	
	$selenium->addTestCase('Add Group and Add Members', 'group-members');
	$selenium->addTestCase('Remove and Blacklist Members', 'group-remove');	
	$selenium->addTestCase('Sanitize Data', 'santize');	
	$selenium->addTestCase('Access', 'access');	
	$selenium->addTestCase('Disallowed Characters', 'characters');		
	$selenium->addTestCase('Cookie Security', 'cookie');
	$selenium->addTestCase('Case Sensitivity', 'case');	
	$selenium->addTestCase('Sidebar Layout', 'sidebar-layout');		
	$selenium->addTestCase('Create user A (Not Really a Test, Just Tired of Making Stupid Accounts)', 'sign-me-up');
	$selenium->addTestCase('Create user B (Not Really a Test, Just Tired of Making Stupid Accounts)', 'sign-me-up2');	
?>