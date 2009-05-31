<div class="messages box">
	<h2>Public Timeline</h2>
	<?php echo $this->load->view('messages/viewlist') ?>
	<?php $this->load->view('users/toggle_threading',array('threading'=>($this->userData)?$this->userData['threading']:0)); ?>
</div>