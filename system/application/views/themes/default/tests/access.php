<?php
	$name = $selenium->randomString(10);
	$password = $selenium->randomString(10);	
	$selenium->caseTitle('Access');
	//list of pages users must be logged in to view	
	$selenium->signOut();	
	$selenium->mustBeLoggedIn('/home');
	$selenium->mustBeLoggedIn('/groups/subscribe/xyz');
	$selenium->mustBeLoggedIn('/groups/add');
	$selenium->mustBeLoggedIn('/groups/inbox/test');
	$selenium->mustBeLoggedIn('/groups/members/test');
	$selenium->mustBeLoggedIn('/settings');
	$selenium->mustBeLoggedIn('/delete');	
	$selenium->mustBeLoggedIn('/users/follow/123');
	$selenium->mustBeLoggedIn('/users/unfollow/123');	
	$selenium->mustBeLoggedIn('/confirm/123');
	//$selenium->mustBeLoggedIn('/deny/123');	
	$selenium->mustBeLoggedIn('/inbox');
	$selenium->mustBeLoggedIn('/sent');
	$selenium->mustBeLoggedIn('/messages/add');
	$selenium->openPage('/admin/flush');	
?>