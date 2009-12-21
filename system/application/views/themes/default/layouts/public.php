<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
	<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="/assets/img/favicon.ico" /> 
	<link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png" />
	<meta http-equiv="Content-type" content="application/xhtml+xml; charset=utf-8"/>
	<?php if (!empty($rss_updates)): ?>
	<link rel="alternate" href="<?php echo $this->config->item('base_url') ?>rss/user/<?php echo $username ?>" title="<?php echo $username ?>'s Updates" type="application/rss+xml" />
	<?php endif ?>
  <link rel="stylesheet" type="text/css" href="/assets/css/style.css" media="all" />
  <link media="handheld, screen and (max-device-width: 480px)" href="/assets/css/iPhone.css" type="text/css" rel="stylesheet" />
  <!-- <link rel="stylesheet" type="text/css" href="/assets/css/print.css" media="print" /> -->
	<title><?php 
		if (!empty($page_title)): 
			echo $this->config->item('service_name') . $this->config->item('title_tag_separator') . $page_title;
		else:
			echo $this->config->item('service_name');
		endif; 
	?></title>
</head>
<body>
<div class="masthead"><div class="container_16">
    <div class="grid_16">
		<?php echo $this->cookie->flashMessage(); ?>
		<h1><a href="/" title="<?php echo $this->config->item('service_name') ?>"><?php echo $this->config->item('service_name') ?></a></h1>
		<?php if (!$this->util->isSection("/signin") && !$this->util->isSection("/signup") && !$this->util->isSection("/reset_password") && !$this->util->isSection("/groups/accept")) { ?>
		<div class="signin-form"><?php $this->load->view('users/signin_form'); ?></div>
		<?php } ?>
		<div class="clearfix"></div>
	</div>
</div></div>
<div class="outer">
    <div id="bd" class="container_12">
		<div class="bd full">
		<?php
		if (!empty($static_view)) { ?>
			<ul class="subnav right" style="padding-right: 20px; margin-top: -10px;">
				<li<?php echo ($this->util->isSection("/home"))?' class="current"':""; ?>><a href="/">Home</a></li>
				<li<?php echo ($this->util->isSection("/about"))?' class="current"':""; ?>><a href="/about">About</a></li>
				<li<?php echo ($this->util->isSection("/faq"))?' class="current"':""; ?>><a href="/faq">FAQ</a></li>
				<li<?php echo ($this->util->isSection("/contact"))?' class="current"':""; ?>><a href="/contact">Contact</a></li>
				<li><a href="/request_invite">Sign Up</a></li>
				<!--
				<li<?php echo ($this->util->isSection("/privacy"))?' class="current"':""; ?>><a href="/privacy">Privacy Policy</a></li>
				<li<?php echo ($this->util->isSection("/terms"))?' class="current"':""; ?>><a href="/terms">Terms of Service</a></li>
				-->
			</ul>
		<?php	
			$this->load->view('static/'.$static_view);	
		} elseif ($this->util->isSection("/signin") || $this->util->isSection("/recover_password") || $this->util->isSection("/reset_password")) {
		?>
			{yield}
		<?php
		} else { 
		?>
			<div class="grid_6">
				<p class="lead-in">Nomcat is an <a href="http://getnomcat.com">open-source tool</a>, built on open-source technologies. It's a lot like Twitter<sup>&trade;</sup>, but aims to do a whole lot more.
				{yield}
			</div>
			<div class="grid_6">
				<img src="/assets/img/nomkat.png" alt="Hello, I'm Nomcat"/>
			</div>
			<div class="clearfix"></div>		
		</div>
		<div class="bd full"><?php /*
				<div class="grid_4 prefix_2"><img src="/assets/img/facebook-app.png"/></div>
				<div class="grid_4">
					<h3>TeamItUp on Facebook</h3>
					<p class="small">Our TeamItUp Facebook application works as your own personal sports network where you can share your teams, schedules, stats, recruit info, and more on your Facebook profile for your friends and teammates to see.</p>
					<p><a href="http://apps.facebook.com/teamitup_app" class="fb-button"><span>Go to Application</span></a></p>
			
				</div>
			*/ ?>
		<?php 
		} 
		?>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		
		<?php $this->load->view('layouts/footer'); ?>
		</div>
	</div>
	</div>
<?php echo $form->testInput('count') ?>
</body>
</html>