<div class="box signup">
	<h2>Request an Account</h2>
	<div class="block">
	<form action="/signup/<?php echo $invite_key ?>" method="post" accept-charset="utf-8">
		<?php echo $this->load->view('users/signupform') ?>
	</form>
	</div>
</div>