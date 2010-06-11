<div class="heading">
	<h2><?php echo $avatar->group($group, "48"); ?> <span><?php echo $html->groupName($group) ?></span></h2>
	<!--<div class="inlineMessage"><p>Private messages are best used when members of a <?php echo $this->config->item('group')?> must be notified immediately.  

These messages are viewed only by <?php echo $this->config->item('group')?> members.

Messages are delivered via email notification/text message as well as on the team page feed and in members' message box.
  
To ensure this feature's value is maximized, please encourage all members to turn on SMS notifications in their <a href="/settings/notifications">settings</a>.</p></div>-->
</div>
<div class="form-share">
	<form action="/messages/add<?php echo $html->sendMeHere() ?>" class="form" method="post" accept-charset="utf-8">		
		<fieldset>
			<p>		
				<label for="message" class="dm">
					Send <?php echo $form->optgroup('to', $group_select, array('first_label'=>'- Select a recipient -')) ?> a private message
				</label>
				<span class="character-count" id="character-count">&nbsp;</span>
			</p>
		</fieldset>
		<fieldset>
			<?php $this->load->view('messages/postform.php') ?>
		</fieldset>
	</form>
</div>
<div id="content">		
	<?php $this->load->view("groups/subnav/group_nav"); ?>	
	<?php echo $this->load->view('messages/viewlist', array('group_page'=>true, 'dm'=>true)) ?>
</div>