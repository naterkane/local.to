<div class="box messages">
	<div class="top">
		<h3>You think you're funny?</h3>
		<form class="postform" action="/messages/add" method="post" accept-charset="utf-8">
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