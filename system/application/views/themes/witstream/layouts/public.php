<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta name="robots" content="noindex,nofollow" />	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<?php if (!empty($rss_updates)): ?>
	<link rel="alternate" href="<?php echo $this->config->item('base_url') ?>rss/user/<?php echo $username ?>" title="<?php echo $username ?>'s Updates" type="application/rss+xml" />
	<?php endif ?>
	<link rel="stylesheet" type="text/css" media="all" href="/css/reset.css?<?php echo time();?>" />
	<link rel="stylesheet" type="text/css" media="all" href="/assets/css/text.css" />
		<link rel="stylesheet" type="text/css" href="/assets/css/960.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="/assets/css/layout.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="/assets/css/nav.css" media="screen" />
		<link rel="stylesheet" type="text/css" media="screen, projection" href="/assets/css/styles.css" />
		<!--[if IE 6]><link rel="stylesheet" type="text/css" href="/assets/css/ie6.css" media="screen" /><![endif]-->
		<!--[if gte IE 7]><link rel="stylesheet" type="text/css" href="/assets/css/ie.css" media="screen" /><![endif]-->
	<title><?php 
		if (!empty($page_title)): 
			echo $page_title;
		else:
			echo $this->config->item('service_name');
		endif; 
	?></title>
</head>
<body>
	<?php echo $this->cookie->flashMessage(); ?>
	<div class="container_12">
		<div class="grid_12">
			<h1 class="logo">
				<a href="/"><?php echo $this->config->item('service_name') ?></a>
			</h1>
			<ul class="nav main">
			<?php $this->load->view('static/navigation'); ?>
			</ul>
		</div>
		<div class="clear"></div>
		
		<div class="grid_4 sidebar">
			{yield}	
		</div>
		<div id="content" class="grid_8 content">
		
			<?php
			if (!empty($static_view)) {
			$this->load->view('static/'.$static_view);	
			} else { 
			
			$this->load->view('messages/public_timeline',$messages);
			} ?>
		</div>
		<?php $this->load->view('layouts/footer'); ?>
	</div>
<?php echo $form->testInput('count') ?>
</body>
</html>