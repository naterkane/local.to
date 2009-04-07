<?php
	$this->selenium->suiteTitle('Microblog Tests');
	$this->selenium->addTestCase('Sign Up', 'sign-up');
	$this->selenium->addTestCase('Add Message', 'add-message');	
	$this->selenium->addTestCase('Follow', 'follow');
	$this->selenium->addTestCase('Access', 'access');	
?>