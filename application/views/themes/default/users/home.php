<div class="form-share">
	<form action="/messages/add?redirect=<?php echo $redirect ?>" class="form" method="post" accept-charset="utf-8">
      	<fieldset>
        	<p>
	          	<label for="message" id="message-context">Share something with everyone</label>
			  	<span class="character-count" id="character-count">&nbsp;</span>
			</p>
        </fieldset>
		<fieldset>
			<?php echo $this->load->view('messages/postform') ?>
			<dl class="tip hide">
				<dt>Tips</dt>
				<dd>use @username to tag a user in your message, i.e. can't wait to buy @naterkane a beer tonight</dd>
				<dd>use !groupname to tag a group in your message, i.e. can't wait for tonight's !nycphp meetup</dd>
			</dl>
		</fieldset>
	</form>
</div>
<div id="content">
	<?php echo $this->load->view('messages/viewlist', $this->userData) ?>
	<div class="box threading">
		<?php $this->load->view('users/toggle_threading',array('threading'=>$this->userData['threading'])); ?>
	</div>
</div>