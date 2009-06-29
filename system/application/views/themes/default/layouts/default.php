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
	<div class="container_16">
		<div class="grid_16">
			<h1 id="branding">
				<a href="/"><?php echo $this->config->item('service_name') ?></a>
			</h1>
		</div>
		<div class="clear"></div>
		<div class="grid_16">
			<ul class="nav main">
			<?php $this->load->view('static/navigation',array('user'=>$this->userData)); ?>
			</ul>
		</div>
		<?php if (empty($this->userData) && isset($username)): ?>
		<div class="grid_4">
			<div class="block">
				<h3><a href="/request_account" class="toggler success">Join today!</a></h3>
				<p>Already using <?php echo $this->config->item('service_name')?>? <a href="/signin">Sign in here</a>.</p>
			</div>
		</div>
		<div class="grid_12">
			<div class="block">
			<h3>Hey there! <strong><?php echo $username ?></strong> is using <?php echo $this->config->item('service_name')?></h3>
			<p><?php echo $this->config->item('service_name')?> is a free service that lets you keep in touch with people through the exchange of quick, frequent answers to one simple question: What are you doing? Join today to start receiving <?php echo $username ?>'s updates.</p>
			</div>
		</div>
		<?php endif; ?>
		<div class="clear"></div>
		
		<div class="grid_4">
			
			<?php $user = (!empty($username))?$this->User->getByUsername($username):$this->userData;

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
						
						<li<?php echo ($this->util->isSection("/home"))?' class="current"':""; ?>>
							<a href="/home">Home</a>
						</li>
						<li<?php echo ($this->util->isSection("/naterkane"))?' class="current"':""; ?>>
							<a href="/replies">@<?php echo $User['username'] ?></a>
						</li>
						<li<?php echo ($this->util->isSection("/favorites"))?' class="current"':""; ?>>
							<a href="/favorites">Favorites <?php if($User['favorites']){ echo "(" . count($User['favorites']) . ")";} ?></a>
						</li>						
						<li<?php echo ($this->util->isSection("/inbox"))?' class="current"':""; ?>>
							<a href="/inbox">Private Mesages</a>
						</li>
						<li<?php echo ($this->util->isSection("/public_timeline"))?' class="current"':""; ?>>
							<a href="/public_timeline">Everyone</a>
						</li>
					</ul>
					
				</div>
				<?php endif; ?>
			<div class="box following">
				<h2>Following</h2>
				<?php if (count($user['following'])>0):
				shuffle($user['following']);
				if (count($user['following'])>25)
				{
					$user['following'] = array_slice($user['following'],0,25);
				}
				?>
				<ul>
					<?php 
					foreach($user['following'] as $following)
					{
						$following = $this->User->get($following);?>
						<li><a href="/<?php echo $following['username']; ?>" alt="<?php echo $following['username']; ?>"><?php echo $avatar->user($following, "24" ); ?></a></li><?php 
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
		</div>
		<div class="grid_12">
		{yield}
		</div>
		<?php $this->load->view('layouts/footer'); ?>
	</div>
<?php echo $form->testInput('count') ?>
<script type="text/javascript" language="javascript" src="/assets/js/nomcat.js"></script>
</body>
</html>