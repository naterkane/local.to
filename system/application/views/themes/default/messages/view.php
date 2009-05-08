<div id="single_post" class="box">
	<?php $this->load->view('messages/viewpost', array('message'=>$message)); ?>
</div>
<?php if (!empty($messages)): ?>
	<div class="messages box">
	<h2>Replies:</h2>
	<div class="block" id="articles">
		<?php $this->load->view('messages/viewlist', array('remove_reply_context'=>true)); ?>
	</div>
	</div>
<?php endif ?>