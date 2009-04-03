<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta name="robots" content="noindex,nofollow" />	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link href="/css/styles.css?1238698095" media="screen, projection" rel="stylesheet" type="text/css" />
	<title><?php if(!empty($title)) echo $title ?></title>
</head>
<body>
<?php
if (!empty($User)) {
	echo "<a href=\"/\">Home</a> <a href=\"/users/home\">" . $User['username'] . "</a> <a href=\"/users/signout\">Sign Out</a>";
} else {
	echo "<a href=\"/\">Home</a> <a href=\"/users/signin\">Sign In</a> | <a href=\"/users/signup\">Sign Up</a>";
}
?>
{yield}
</body>
</html>