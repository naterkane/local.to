<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta name="robots" content="noindex,nofollow" />	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
		<div class="clear"></div>
		
		<div class="grid_10">
			<?php
			if (!empty($static_view)) {
			$this->load->view('static/'.$static_view);	
			} else { 
				?>
			<div class="box"><div class="block"><h1>welcome!</h1></div></div>
			<div class="grid_5 alpha"><div class="box alpha">
				<h4>this is a heading</h4>
				<p>Nunc neque. Integer molestie sollicitudin neque. Maecenas id lectus eu enim vulputate molestie. Sed ullamcorper dapibus neque. Pellentesque luctus blandit dui. Nunc facilisis, leo ut fringilla aliquam, nulla risus imperdiet urna, ut aliquam erat elit et turpis. Duis neque. Fusce dignissim accumsan augue. In vel velit. Mauris facilisis, nulla eget ultricies ultrices, libero lectus molestie ipsum, eu sollicitudin metus lorem et ligula. Phasellus vitae erat. Integer tempus lobortis tortor. Ut eu urna ut ligula malesuada eleifend. Nullam lacinia imperdiet mauris. In tincidunt, ipsum vel euismod tempor, elit sem eleifend nisi, at sollicitudin tortor augue at nisi. Nam velit.</p>
				<h5>a sub heading</h5>
				<p>Praesent hendrerit sem tincidunt erat. Nullam convallis lectus et urna. Praesent egestas erat ac est. Maecenas tempor. Sed egestas ipsum ut eros. Nunc interdum ullamcorper magna. Praesent ut quam. Duis nec libero laoreet odio venenatis varius. Aliquam sapien dui, viverra ac, pretium eget, adipiscing eget, lacus. Vivamus scelerisque sem vel libero.</p>
				<p><a href="#">check out this link &raquo;</a></p>
				</div></div>
			<div class="grid_5 omega"><div class="box omega">
				<h4>and another heading</h4>
				<p>Duis at nisl et dolor placerat ullamcorper. Nunc adipiscing dui in augue. Praesent rhoncus. Maecenas ligula sem, pretium vitae, vestibulum ut, imperdiet nec, magna. In ante. Curabitur non nibh. Curabitur ullamcorper adipiscing felis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Suspendisse potenti. In aliquet commodo dolor. Etiam hendrerit vehicula justo.</p>
				<p>Sed velit orci, venenatis id, dapibus vitae, lacinia quis, ante. Sed sit amet sapien. Curabitur tortor. Maecenas congue. Etiam non nunc sed turpis auctor vulputate. Integer ornare venenatis erat. In hac habitasse platea dictumst. In hac habitasse platea dictumst. Suspendisse potenti. Quisque urna nisi, rutrum non, faucibus vitae, eleifend eu, tortor. Etiam non libero. Morbi odio tellus, dictum quis, tincidunt eget, bibendum sed, lectus. Donec tincidunt venenatis nulla. Morbi quis orci. Ut dictum metus id ligula. Cras molestie. Nulla vel tortor. Cras lacus. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent in ante at nulla sagittis volutpat.</p>
			</div></div>
			<?php 
			
			
			} ?>
		</div>
		<div class="grid_6">
		{yield}
		</div>
		<?php $this->load->view('layouts/footer'); ?>
	</div>
<?php echo $form->testInput('count') ?>
</body>
</html>