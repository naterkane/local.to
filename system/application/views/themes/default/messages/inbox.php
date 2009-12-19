<div class="heading">
	<h2 style="display:inline;">Private Messages<ul class="subnav right" style="display: inline;padding-right: 10px;">
		<li class="current"><?php echo $html->link('Inbox', '/inbox'); ?></li>
		<li><?php echo $html->link('Sent', '/sent') ?></li>
	</ul></h2><!-- 
	<p class="inlineMessage">You can exchange private messages with the users you follow who also follow you, as well as with the members of your team(s).</p>-->
</div>
<div class="form-share">
	<?php $this->load->view('messages/dms_form.php') ?>
</div>
<div id="content">
	
	<div class="clearfix"></div>
	<?php echo $this->load->view('messages/viewlist', array('dm'=>true)) ?>
</div>