<div class="messages box">
	<h2>Public Timeline</h2>
	<?php echo $this->load->view('messages/viewlist') ?>
	<div class="box">
		<?php $this->load->view('static/toggle_threading'); ?>
	</div>
</div>