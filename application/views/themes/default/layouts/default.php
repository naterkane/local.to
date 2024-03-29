<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta name="robots" content="noindex,nofollow" />	
	<link rel="shortcut icon" type="image/vnd.microsoft.icon" href="/assets/img/favicon.ico" /> 
	<link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png" />
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
	<meta http-equiv="Content-type" content="application/xhtml+xml; charset=utf-8"/>
	<?php if (!empty($rss_updates)): ?>
	<link rel="alternate" href="<?php echo $this->config->item('base_url') ?>rss/user/<?php echo $username ?>" title="<?php echo $username ?>'s Updates" type="application/rss+xml" />
	<?php endif; ?>
  <link rel="stylesheet" type="text/css" href="/assets/css/style.css" media="all" />
  <!--<link media="only screen and (max-device-width: 480px)" href="/assets/css/iPhone.css" type="text/css" rel="stylesheet" />
   <link rel="stylesheet" type="text/css" href="/assets/css/print.css" media="print" /> -->
	<title><?php 
		if (!empty($page_title)): 
			echo $this->config->item('service_name') . $this->config->item('title_tag_separator') . $page_title;
		else:
			echo $this->config->item('service_name');
		endif; 
	?></title>
</head>
<body id="top">
	<?php echo $this->cookie->flashMessage(); ?>
<div class="masthead"><div class="container_16">
    <div class="grid_16">
    	
	    <h1><a href="/" title="<?php echo $this->config->item('service_name') ?>"><?php echo $this->config->item('service_name') ?></a></h1>
	    <div class="nav">

			<!--  <ul class="nav-main">
	      		<?php $this->load->view('static/navigation',array('user'=>$this->userData)); ?>
			</ul>
			-->
			<ul class="nav-util">
			<?php if ($this->isSignedIn): ?>
			<?php $this->load->view('static/navigation',array('user'=>$this->userData)); ?>
				<!--<li><a href="/Search" title="Search" id="nav-search">Search</a></li>-->
				<li><a href="/settings" title="Settings" id="nav-settings">Settings</a></li>
				<li><a href="/users/signout" id="nav-signout">Sign Out</a></li>
			<?php else: ?>
				<li<?php echo ($this->util->isSection("/about"))?' class="current"':""; ?>><a href="/about">About</a></li>
				<li<?php echo ($this->util->isSection("/signin"))?' class="current"':""; ?>>
					<?php if (!empty($sendMeHere)): ?>
						<a href="/signin<?php echo $html->sendMeHere(); ?>">Sign In</a>
					<?php else: ?>
						<a href="/signin">Sign In</a>
					<?php endif ?>
				</li>
				<li<?php echo ($this->util->isSection("/request_invite"))?' class="current"':""; ?>><a href="/request_invite">Sign Up</a></li>
	      	<?php endif; ?>
			</ul>
	    </div>
		
	 </div>
	 <div class="clearfix"></div></div>
	</div>
<div class="outer container_16">

    <div id="bd" class="container_16 default">
    <div id="main" class="grid_12">
		<?php if (!$this->isSignedIn): ?>
			<?php if (!empty($profile)): ?>
			<div class="grid_12" id="sb_signup">
				<p class="lead-in">Hey there! <strong><?php echo $profile['username'] ?></strong> is using <?php echo $this->config->item('service_name')?></p>
				<p><?php echo $this->config->item('service_name')?> is a free tool that we hope makes talking to friends online a little easier to manage.  Join today to start receiving <?php echo $profile['username'] ?>'s updates.<br/></p>
				
			</div>
			<div class="clearfix"></div>
			<?php endif; ?>
			<div class="inlineMessage neutral">
				<p><a href="/request_invite" class="button">Join today!</a> &nbsp; Already using <?php echo $this->config->item('service_name')?>? <a href="/signin?redirect=<?php echo substr($_SERVER['PHP_SELF'],10); ?>">Sign in here</a>.<br/></p>
			</div>
		<?php endif; ?>
		{yield}	
		</div>
    	<div id="sidebar" class="grid_4">
	      	<div class="content">	
				<?php
				if ($this->loadGroupSidebar) 
				{
					$this->load->view('groups/sidebar/main');
				} 
				else 
				{
					$this->load->view('users/sidebar/main');
				}
				?><div class="clear"></div>
			</div>
		</div>
		
		<?php $this->load->view('layouts/footer'); ?>
	</div>
</div>
<?php echo $form->testInput('count') ?>
<?php //if ($this->config->item('testing') == true): ?>
<script type="text/javascript" src="/assets/js/nomcat.js"></script>
<?php /* else: ?>
<script type="text/javascript" src="/assets/js/nomcat-min.js"></script>
<?php endif; */ ?>
</body>
</html>