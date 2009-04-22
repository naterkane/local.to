<?php
	$this->selenium->suiteTitle('Microblog Tests');
	$this->selenium->addTestCase('Sign Up', 'sign-up');
	$this->selenium->addTestCase('Change Settings', 'settings');	
	$this->selenium->addTestCase('Add Message', 'add-message');	
	$this->selenium->addTestCase('Follow', 'follow');
	$this->selenium->addTestCase('Add Group and Subscribe', 'group');	
	$this->selenium->addTestCase('Access', 'access');	
?>