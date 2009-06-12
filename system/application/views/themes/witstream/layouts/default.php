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
	<link rel="stylesheet" type="text/css" media="all" href="/css/reset.css" />
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
	
	<div class="container_12">
		<div class="grid_12">
			<h1 class="logo">
				<a href="/"><?php echo $this->config->item('service_name') ?></a>
			</h1>
			<ul class="nav main">
			<?php $this->load->view('static/navigation',array('user'=>$this->userData)); ?>
			</ul>
			
		</div>
		<div class="clear"></div>
		
		<div class="grid_4 sidebar">
			<?php if (empty($this->userData) && isset($username)): ?>
			
			<div class="box signup">
				<h3><a href="/request_account" class="toggler">Join today!</a></h3>
				<p>Already using <?php echo $this->config->item('service_name')?>? <a href="/signin">Sign in here</a>.</p>
			</div>
				
			<?php 
			endif;
			$user = (!empty($username))?$this->User->getByUsername($username):$this->userData;

			if (!empty($group)): ?>
			<div class="box profile">
				<?php
				$this->load->view('groups/profilesidebar',array('group'=>$group));
				 ?>
			</div>
				<?php
				$this->load->view('groups/memberssidebar',array('group'=>$group));
				
			elseif (!empty($user)): ?>
			<div class="box profile">
				<?php $this->load->view($this->sidebar,array('user'=>$user)); ?>
			</div>
				<?php if (!empty($User)  && $user['username'] == $this->userData['username']): ?>
				<div class="box">
					<ul class="menu">
						<li<?php echo ($this->util->isSection("/public_timeline"))?' class="current"':""; ?>>
							<a href="/public_timeline">Everyone</a>
						</li>
						<li<?php echo ($this->util->isSection("/home"))?' class="current"':""; ?>>
							<a href="/home">Home</a>
						</li>
						<li<?php echo ($this->util->isSection("/mentions"))?' class="current"':""; ?>>
							<a href="/replies">Mentions</a>
						</li>
						<li<?php echo ($this->util->isSection("/inbox") || $this->util->isSection("/outbox"))?' class="current"':""; ?>>
							<a href="/inbox">Private Messages</a>
						</li>
						<li<?php echo ($this->util->isSection("/favorites"))?' class="current"':""; ?>>
							<a href="/favorites">Favorites</a>
						</li>
						
					</ul>
					
				</div>
				<?php endif; ?>
			
			<h3>Following</h3>
			<div class="box following">
				<?php if (count($user['following'])>0):
				shuffle($user['following']);
				if (count($user['following'])>25)
				{
					$user['following'] = array_slice($user['following'],0,25);
				}
				?>
				<ul class="following">
					<?php 
					foreach($user['following'] as $following)
					{
						$following = $this->User->get($following);?>
						<li>
							<a href="/<?php echo $following['username']; ?>" alt="<?php echo $following['username']; ?>"><?php echo $avatar->user($following, "24" ); ?></a>
							<p class="username"><?php echo $following['username']; ?></p>
							<?php if (!empty($following['realname'])){?><p class="realname"><?php echo $following['realname']; ?></p><?php } ?>
							<p class="links"><a href="/<?php echo $following['username']; ?>"><?php echo $following['username']; ?></a> | <a href="/users/following/<?php echo $following['username']; ?>">Friends</a></p>
						</li><?php 
					} 
					?>
				</ul>
				<?php else: ?>
					<p><?php echo $user['username'] ?> is not currently following anyone.</p>
				<?php endif; ?>
				
				</div>
			<?php else: ?>
			<?php $this->load->view('users/signin'); ?>
			
			
			<?php endif; ?>
			
			<span style="display:block;padding:0;width:100px;overflow:visible;"><a href="#"><img src="/ads/sidebar-300x250.gif" style="border:1px solid #ccc;margin:0 auto;padding:0"/></a></span>
			</div>
		<div id="content" class="grid_8">
		<?php echo $this->cookie->flashMessage(); ?>
		{yield}	
		</div>
		<?php $this->load->view('layouts/footer'); ?>
	</div>
<?php echo $form->testInput('count') ?>
</body>
</html>