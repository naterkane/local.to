<?php
	$name = $this->selenium->randomString(10);
	$password = $this->selenium->randomString(10);	
	$this->selenium->caseTitle('Sign Up');
	$this->selenium->signOut();	
	$this->selenium->signUp($name, $password);
	$this->selenium->signIn($name, $password);
	$this->selenium->openPage('/' . $name);
	$this->selenium->openPage('/admin/flush');	
?>