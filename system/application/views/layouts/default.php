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
	<p>
		<a href="/">Microblog</a>
		<?php
		if (!empty($User)) {
			echo "<a href=\"/home\">Home</a> <a href=\"/{$User['username']}\">Profile</a> <a href=\"/signout\">Sign Out</a>";
		} else {
			echo "<a href=\"/signin\">Sign In</a> <a href=\"/signup\">Sign Up</a>";
		}
		?>
	</p>
<?php echo $this->cookie->flashMessage(); ?>
{yield}
</body>
</html>