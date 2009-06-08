<div class="form-share">
      <span class="character-count" id="character-count"></span>
      <form action="/messages/add" class="form" method="post" accept-charset="utf-8">
      	<fieldset>
          <label for="message" id="message-context">What are you up to?</label>
        </fieldset>
		<fieldset>
		<?php echo $this->load->view('messages/postform') ?>
		</fieldset>
	</form>
</div>
	<?php echo $this->load->view('messages/viewlist', $this->userData) ?>
	<div class="box threading">
		<?php $this->load->view('users/toggle_threading',array('threading'=>$this->userData['threading'])); ?>
	</div>
</div>