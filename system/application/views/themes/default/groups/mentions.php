<div class="heading">
	<h2><?php echo $avatar->group($group, "48"); ?> <span><?php echo $html->groupName($group) ?></span></h2>
</div>
<div id="content">		
	<?php $this->load->view("groups/subnav/group_nav"); ?>	
	<div class="inlineMessage">
		<p>Updates that mention <?php echo $html->groupName($group) ?> (!<a href="/group/<?php echo $group['name']; ?>"><?php echo $group['name']; ?></a>) elsewhere on <?php echo $this->config->item('service_name'); ?>.</p>
	</div>
	<?php echo $this->load->view('messages/viewlist'); ?>
</div>