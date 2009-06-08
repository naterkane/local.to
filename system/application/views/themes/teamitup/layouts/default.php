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
  <link rel="stylesheet" type="text/css" href="/assets/css/reset-fonts-grids.css" media="all" />
  <link rel="stylesheet" type="text/css" href="/assets/css/style.css" media="all" />
  <!-- <link rel="stylesheet" type="text/css" href="/assets/css/print.css" media="print" /> -->
	<title><?php 
		if (!empty($page_title)): 
			echo $page_title;
		else:
			echo $this->config->item('service_name');
		endif; 
	?></title>
</head>
<body>
	
<!-- =outer -->
<div class="outer">
  <!-- =hd -->
  <div id="hd">
    <h1><a href="/" title="<?php echo $this->config->item('service_name') ?>"><?php echo $this->config->item('service_name') ?></a></h1>
  </div><!-- /hd -->
  

  <div class="inner">

    <!-- =nav -->
    <div id="nav">
      <!-- =main-nav -->
      <ul class="main-nav">
		<?php $this->load->view('static/navigation',array('user'=>$this->userData)); ?>
     </ul><!-- /main-nav -->

      <!-- =sec-nav -->
      <ul class="sec-nav">
        <!--<li><a href="/Search" title="Search">Search</a></li>-->
        <li><a href="/settings" title="Settings">Settings</a></li>
		<li><a href="/about" title="About">About</a></li>
        <li><a href="/users/signout">Sign Out</a></li>
      </ul><!-- /sec-nav -->
      
    </div><!-- /nav -->
	
	
    <!-- =bd -->
    <div id="bd">
      <div class="sidebar">
      		
		<?php if (empty($this->userData) && isset($username)): ?>
        <div class="user-box">
				<h3><a href="/request_account" class="toggler success">Join today!</a></h3>
				<p>Already using <?php echo $this->config->item('service_name')?>? <a href="/signin">Sign in here</a>.</p>
		</div>
		<div class="grid_12">
			<div class="block">
			<h3>Hey there! <strong><?php echo $username ?></strong> is using <?php echo $this->config->item('service_name')?></h3>
			<p><?php echo $this->config->item('service_name')?> is a free service that lets you keep in touch with people through the exchange of quick, frequent answers to one simple question: What are you doing? Join today to start receiving <?php echo $username ?>'s updates.</p>
			</div>
		</div>
		<?php endif; ?>
		
		<?php $user = (!empty($username))?$this->User->getByUsername($username):$this->userData;

		if (!empty($group)): ?>
			<div class="user-box">
				<?php
				$this->load->view('groups/profilesidebar',array('group'=>$group));
				 ?>
			</div>
				<?php
				$this->load->view('groups/memberssidebar',array('group'=>$group));
				
		elseif (!empty($user)): ?>
			
			<div class="user-box">
				<?php $this->load->view($this->sidebar,array('user'=>$user)); ?>
			</div>
			
			<?php if (!empty($User)  && $user['username'] == $this->userData['username']): ?>
				
			
			<ul class="message-stats">
	          <li<?php echo ($this->util->isSection("/home"))?' class="active"':""; ?>><h3><a href="/home" title="Messages">Messages</a></h3></li>
	          <li<?php echo ($this->util->isSection("/mentions"))?' class="active"':""; ?>><h3><a href="/mentions" title="Mentions">Mentions <?php if($User['mentions']){ echo '<span class="count">' . count($User['mentions']) . '</span>';} ?></a></h3></li>
	          <li<?php echo ($this->util->isSection("/inbox"))?' class="active"':""; ?>><h3><a href="/inbox" title="Private">Private <?php if (!empty($this->userData['inbox'])){echo '<span class="count">',count($this->userData['inbox']).'</span>';} ?>naterk</a></h3></li>
	          <li<?php echo ($this->util->isSection("/favorites"))?' class="active"':""; ?>><h3><a href="/favorites" title="Favorites">Favorites <?php if($User['favorites']){ echo '<span class="count">' . count($User['favorites']) . "</span>";} ?></a></h3></li>
	          <li<?php echo ($this->util->isSection("/public_timeline"))?' class="active"':""; ?>><h3><a href="/public_timeline" title="Everyone">Everyone</a></h3></li>
	        </ul>
						
		<?php endif; ?>
			<div class="teammates">
          		<h3>Following <span class="count">407</span></h3>
				<?php if (count($user['following'])>0):
				shuffle($user['following']);
				if (count($user['following'])>25)
				{
					$user['following'] = array_slice($user['following'],0,25);
				}
				?>
				<ul class="clearfix">
					<?php 
					foreach($user['following'] as $following)
					{
						$following = $this->User->get($following);?>
						<li><a href="/<?php echo $following['username']; ?>" class="thumb-sm" title="<?php echo $following['username']; ?>"><?php echo $avatar->user($following, "24" ); ?></a></li><?php 
					} 
					?>
				</ul>
				<a href="#" class="view-all-link" title="View All">View All</a>
				<?php else: ?>
					<p><?php echo $user['username'] ?> is not currently following anyone.</p>
				<?php endif; ?>
			</div>
			<?php else: ?>
			<?php $this->load->view('users/signin'); ?>
			<?php endif; ?>
		</div>
		<div class="bd">
		<?php echo $this->cookie->flashMessage(); ?>
		{yield}	
		</div>
		<?php $this->load->view('layouts/footer'); ?>
	</div>
<?php echo $form->testInput('count') ?>
<script type="text/javascript" language="javascript" src="/assets/js/teamitup.js"></script>
</body>
</html>