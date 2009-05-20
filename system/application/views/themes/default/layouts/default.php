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
	<?php echo $this->cookie->flashMessage(); ?>
	<div class="container_16">
		<div class="grid_16">
			<h1 id="branding">
				<a href="/">MicroBlog</a>
			</h1>
		</div>
		<div class="clear"></div>
		<div class="grid_16">
			<ul class="nav main">
			<?php $this->load->view('static/navigation'); ?>
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
				//var_dump($user); 
				//echo $this->sidebar; 
				
				
			
			if (!empty($user)): ?>
			<div class="box">
			<?php $this->load->view($this->sidebar,array('user'=>$user)); ?>
			</div>
			<div class="box following">
				<h2>Following</h2>
				<?php if (count($user['following'])):?>
				<ul>
					<?php 
					foreach($user['following'] as $following)
					{
						$following = $this->User->get($following);?>
						<li><a href="/<?php echo $following['username']; ?>" alt="<?php echo $following['username']; ?>"><?php echo $gravatar->img( $following['email'],"24" ); ?></a></li><?php 
					} 
					?>
				</ul>
				<?php else: ?>
					<p><?php echo $user['username'] ?> is not currently following anyone.</p>
				<?php endif; ?>
			</div>
			<?php 
			elseif (!empty($User)): ?>
			<div class="box">
				<ul class="menu">
					<li>
						<a href="/home">Home</a>
					</li>
					<li>
						<a href="/<?php echo $User['username'] ?>">@<?php echo $User['username'] ?></a>
					</li>
					<li>
						<a href="#">Private Mesages</a>
					</li>
					<li>
						<a href="/public_timeline">Everyone</a>
					</li>
				</ul>
				
			</div>
			
			<div class="box following">
				<h2>Following</h2>
				<?php if (count($User['following'])):?>
				<ul>
					<?php foreach($User['following'] as $following){
						$following = $this->User->get($following);
						?>
					<li><a href="/<?php echo $following['username']; ?>" alt="<?php echo $following['username']; ?>"><?php echo $gravatar->img( $following['email'],"24" ); ?></a></li>
					<?php } ?>
				</ul>
				<?php else: ?>
					<p>You are not currently following anyone, take a look at the <a href="/public_timeline">public timeline</a> to see if anyone catches your eye.</p>
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
</body>
</html>