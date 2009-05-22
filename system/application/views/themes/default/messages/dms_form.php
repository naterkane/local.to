<?php if ($friend_select): ?>
	<form action="/messages/add" method="post" accept-charset="utf-8">
		<h3>Send <?php echo $form->select('to', $friend_select) ?> a direct message</h3>		
		<fieldset>
		<?php echo $this->load->view('messages/postform') ?>
		</fieldset>
	</form>
<?php else: ?>
	<p>You have no followers. You can only send direct messages if you have followers.</p>
<?php endif ?>