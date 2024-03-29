<div class="heading">
	<h2><?php echo $avatar->group($group, "48",true); ?> <span><?php echo $html->groupName($group) ?></span></h2>
</div>
<?php if (!empty($group['im_a_member'])): ?>
<div class="form-share">
    <form action="/messages/add?redirect=<?php echo $redirect ?>" class="form" method="post" accept-charset="utf-8">
		<fieldset>
			<p>
				<label for="message" id="message-context" class="group">Share something with <?php echo $html->groupName($group) ?></label>
				<span class="character-count" id="character-count">&nbsp;</span>
			</p>
		</fieldset>
		<fieldset>
			<?php echo $this->load->view('messages/postform', array('group_page'=>true)) ?>
			<dl class="tip hide">
				<dt>Tips</dt>
				<dd>use @username to tag a user in your message, i.e. can't wait to buy @naterkane a beer tonight.</dd>
				<dd>use !<?php echo $this->config->item('group')?>name to tag a <?php echo $this->config->item('group')?> in your message, i.e. can't wait for tonight's !nycphp meetup</dd>
			</dl>
		</fieldset>
	</form>
</div>
<?php endif; ?>
<div id="content">		
	<?php $this->load->view("groups/subnav/group_nav"); ?>
	<?php if (empty($group['im_a_member'])): ?>
	<div class="inlineMessage">
		<p>Currently, you are not a member of this <?php echo $this->config->item('group')?> and cannot view its updates.  Instead, you can see mentions of the <?php echo $this->config->item('group')?> made elsewhere on <?php echo $this->config->item('service_name')?>.</p>
	</div>
	<?php endif; ?>	
	<?php echo $this->load->view('messages/viewlist', array('group_page'=>true)); ?>
	<div class="box threading">
		<?php $this->load->view('users/toggle_threading',array('threading'=>$this->userData['threading'])); ?>
	</div>
</div>