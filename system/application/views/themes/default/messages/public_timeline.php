<div class="form-share">
	<form action="/messages/add?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="form" method="post" accept-charset="utf-8">
      	<fieldset>
        	<p>
	          	<label for="message" id="message-context">Share something with everyone</label>
			  	<span class="character-count" id="character-count">&nbsp;</span>
			</p>
        </fieldset>
		<fieldset>
			<?php echo $this->load->view('messages/postform') ?>
			<dl class="tip">
				<dt>Tips</dt>
				<dd>use @username to tag a user in your message, i.e. congrats on the win @charlieperry</dd>
				<dd>use !teamname to tag a team in your message, i.e. just had a huge win over !ulacrosse</dd>
			</dl>
		</fieldset>
	</form>
</div>
<div id="content">
	<?php echo $this->load->view('messages/viewlist') ?>
	<div class="box threading">
		
		<?php $this->load->view('users/toggle_threading',array('threading'=>($this->userData)?$this->userData['threading']:0)); ?>
	</div>
</div>