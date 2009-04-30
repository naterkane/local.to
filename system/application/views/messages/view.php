<div id="single_post">
	<?php $this->load->view('messages/viewpost', array('message'=>$message)); ?>
</div>
<?php if (!empty($messages)): ?>
	<h3>Replies:</h3>
	<?php $this->load->view('messages/viewlist', array('remove_reply_context'=>true)); ?>
<?php endif ?>