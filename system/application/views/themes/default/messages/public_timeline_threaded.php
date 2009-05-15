<div class="messages box">
	<h2>Public Timeline</h2>
	<?php echo $this->load->view('messages/viewlist', $threaded = true) ?>
	<div class="box">
		
		<p>Thread replies: <a href="/public_timeline_threaded">On</a> | <em>Off</em></p>
	</div>
</div>