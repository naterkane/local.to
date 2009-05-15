<div class="box messages">
	<div class="box">
	<div class="block">
		<h3>What are you doing?</h3>
		<form action="/messages/add" method="post" accept-charset="utf-8">
			<fieldset>
			<?php echo $this->load->view('messages/postform') ?>
			</fieldset>
		</form>
	</div>
	</div>
		<?php echo $this->load->view('messages/viewlist', $threaded) ?>
	<div class="box">
		<?php $this->load->view('static/toggle_threading'); ?>
	</div>
</div>