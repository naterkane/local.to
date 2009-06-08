<div class="messages box">
	<div class="top short"><h3>Public Timeline</h3>
	</div>
	<?php echo $this->load->view('messages/viewlist') ?>
	<div class="threading">
	<?php $this->load->view('users/toggle_threading',array('threading'=>($this->userData)?$this->userData['threading']:0)); ?>
	</div>
</div>