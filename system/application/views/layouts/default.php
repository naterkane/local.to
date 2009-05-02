<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta name="robots" content="noindex,nofollow" />	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" type="text/css" media="screen, projection" href="/css/styles.css?<?php echo time();?>" />
	<link rel="stylesheet" type="text/css" media="all" href="/css/reset.css?<?php echo time();?>" />
	<link rel="stylesheet" type="text/css" media="all" href="/css/text.css?<?php echo time();?>" />
		<link rel="stylesheet" type="text/css" href="/css/960.css?<?php echo time();?>" media="screen" />
		<link rel="stylesheet" type="text/css" href="/css/layout.css?<?php echo time();?>" media="screen" />
		<link rel="stylesheet" type="text/css" href="/css/nav.css?<?php echo time();?>" media="screen" />
		<!--[if IE 6]><link rel="stylesheet" type="text/css" href="/css/ie6.css?<?php echo time();?>" media="screen" /><![endif]-->
		<!--[if gte IE 7]><link rel="stylesheet" type="text/css" href="/css/ie.css?<?php echo time();?>" media="screen" /><![endif]-->
	<title><?php 
		if (!empty($page_title)): 
			echo $page_title;
		else:
			echo "microblog";
		endif; 
	?></title>
</head>
<body>
	<div class="container_16">
		<?php echo $this->cookie->flashMessage(); ?>
		<div class="grid_16">
			<h1 id="branding">
				<a href="/">MicroBlog</a>
			</h1>
		</div>
		<div class="clear"></div>
		<div class="grid_16">
			<ul class="nav main">
			<?php
			if (!empty($User)) {
				?>
				<li><a href="/home">Home</a></li>
				<li><a href="/<?php echo $User["username"] ?>">Profile</a></li>
				<li><a href="/groups">Groups</a></li>
				<li><a href="/settings">Settings</a></li>
				<li><a href="/users/signout">Sign Out</a></li>
				<?php
			} else {
				?>
				<li><a href="/">Home</a></li>
				<li><a href="/users/signin">Sign In</a></li>
				<li><a href="/users/signup">Sign Up</a></li>
				<?php
			}
			?>
			</ul>
		</div>
		<div class="clear"></div>
		<div class="grid_4">
			<div class="box">
				<?php $this->wick->light('users/sidebarprofile'); ?>
			
			
				<ul class="menu">
					<li>
						<a href="/home">Home</a>
					</li>
					<li>
						<a href="#">@<?php echo $User['username'] ?></a>
					</li>
					<li>
						<a href="#">Private Mesages</a>
					</li>
					<li>
						<a href="/public_timeline">Everyone</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="grid_12">
		{yield}
		</div>
		<?php $this->load->view('layouts/footer'); ?>
	</div>
<?php echo $form->testInput('count') ?>
</body>
</html>