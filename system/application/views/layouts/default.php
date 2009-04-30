<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta name="robots" content="noindex,nofollow" />	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link href="/css/styles.css?<?php echo time();?>" media="screen, projection" rel="stylesheet" type="text/css" />
	<title><?php 
		if (!empty($page_title)): 
			echo $page_title;
		else:
			echo "microblog";
		endif; 
	?></title>
</head>
<body>
	<p>
		<a href="/">[Microblog Logo]</a>
		<?php
		if (!empty($User)) {
			?>
			<a href="/home">Home</a> 
			<a href="/<?php echo $User["username"] ?>">Profile</a>
			<a href="/groups">Groups</a>
			<a href="/settings">Settings</a>
			<a href="/public_timeline">Everyone</a>
			<a href="/users/signout">Sign Out</a>
			<?php
		} else {
			?>
			<a href="/users/signin">Sign In</a>
			<a href="/users/signup">Sign Up</a>
			<?php
		}
		?>
	</p>
<?php echo $this->cookie->flashMessage(); ?>
{yield}
<?php echo $form->testInput('count') ?>
</body>
</html>