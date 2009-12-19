<div class="heading">
	<h2 style="display:inline;">Private Messages<ul class="subnav right" style="display: inline;padding-right: 10px;">
		<li><?php echo $html->link('Inbox', '/inbox'); ?></li>
		<li class="current"><?php echo $html->link('Sent', '/sent') ?></li>
	</ul></h2>
</div>
<div class="form-share">
	<?php $this->load->view('messages/dms_form.php') ?>
</div>
<div id="content">
	<ul class="subnav">
		<li><?php echo $html->link('Inbox', '/inbox'); ?></li>
		<li class="current"><?php echo $html->link('Sent', '/sent') ?></li>
	</ul>
	<div class="clearfix"></div>
	<?php echo $this->load->view('messages/viewlist') ?>
</div>