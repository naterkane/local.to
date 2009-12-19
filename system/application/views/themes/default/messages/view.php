<div id="heading">
	<div class="message">
	<?php $this->load->view('messages/viewpost', array('message'=>$message)); ?>
	</div>
</div>

<div id="content">
<?php if (!empty($messages)): ?>
	<h3>Replies:</h3>
	
		<?php //var_dump($messages);echo " threading " ;var_dump($messages['threading']); ?>
		<?php $this->load->view('messages/viewlist', array('messages'=>$messages)); ?>
	
<?php endif; ?>
</div>