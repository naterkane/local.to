<div class="clear"></div>
<div class="footer" id="site_info">
	<ul class="subnav"><li><a href="/">Home</a></li><li><a href="/about">About</a></li><li><a href="/faq">FAQ</a></li><li><a href="/contact">Contact</a></li><li><a href="http://getnomcat.com">Developers</a></li><li><a href="/privacy">Privacy Policy</a></li><li><a href="/terms">Terms of Service</a></li><li class="right">&copy; 2009 NOM LLC.</li></ul>
<div class="clear"></div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<!--[if IE 6]>
	<script type="text/javascript" src="/js/jquery.ie6blocker.js"></script>
<![endif]-->
<script type="text/javascript" src="/js/nomcat.js"></script>
<?php 
// this is to inject any analytics or crm tool javascript or misc code
echo $this->config->item("footer_code");
?>