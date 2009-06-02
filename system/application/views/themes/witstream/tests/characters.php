<?php
	$selenium->caseTitle('Disallowed Characters');
	$selenium->openPage('/home/:');
	$selenium->openPage('/home/~');
	$selenium->openPage('/home/.');
	$selenium->openPage('/home/+');
	$selenium->openPage('/home/=');
	$selenium->openPage('/home/-');
	$selenium->openPage('/home/_');
	$selenium->openPage('/home/?');	
	$selenium->openPage('/home/#');
	$selenium->openPage('/home/%50');			
	$selenium->badChar('/home/!');
	$selenium->badChar('/home/@');
	$selenium->badChar('/home/^');
	$selenium->badChar('/home/&');
	$selenium->badChar('/home/*');
	$selenium->badChar('/home/(');
	$selenium->badChar('/home/)');
	$selenium->badChar('/home/\'');
	$selenium->badChar('/home/"');
	$selenium->badChar('/home/>');
	$selenium->badChar('/home/<');
	$selenium->badChar('/home/,');
	$selenium->badChar('/home/;');
	$selenium->badChar('/home/{');
	$selenium->badChar('/home/}');	
?>