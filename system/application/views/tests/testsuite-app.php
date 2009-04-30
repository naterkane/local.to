<?php
	$selenium->suiteTitle('Microblog Tests');
	$selenium->addTestCase('Sign Up', 'sign-up');
	$selenium->addTestCase('Change Settings', 'settings');	
	$selenium->addTestCase('Add Message', 'add-message');
	$selenium->addTestCase('Threading', 'threading');	
	$selenium->addTestCase('Follow', 'follow');
	$selenium->addTestCase('Add Group and Subscribe', 'group');
	$selenium->addTestCase('Add Group and Change Profile', 'group-settings');	
	$selenium->addTestCase('Access', 'access');	
?>