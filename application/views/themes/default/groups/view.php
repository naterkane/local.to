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
			<dl class="tip">
				<dt>Tips</dt>
				<dd>use @username to tag a user in your message, i.e. congrats on the win @charlieperry</dd>
				<dd>use !teamname to tag a team in your message, i.e. just had a huge win over !ulacrosse</dd>
			</dl>
		</fieldset>
	</form>
</div>
<?php endif; ?>
<div id="content">		
	<?php $this->load->view("groups/subnav/group_nav"); ?>
	<?php if (empty($group['im_a_member'])): ?>
	<div class="inlineMessage">
		<p>Currently, you are not a member of this team and cannot view its updates.  Instead, you can see mentions of the team made elsewhere on Teamitup.</p>
	</div>
	<?php endif; ?>	
	<?php echo $this->load->view('messages/viewlist', array('group_page'=>true)); ?>
	<div class="box threading">
		<?php $this->load->view('users/toggle_threading',array('threading'=>$this->userData['threading'])); ?>
	</div>
</div>