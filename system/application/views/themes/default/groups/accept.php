<p>You must sign up or sign in to join a group.</p>
<div class="box">
	<h2>Sign Up</h2>
	<div class="block">
	<form action="/groups/accept/<?php echo $key ?>" method="post" accept-charset="utf-8">
		<?php echo $this->load->view('users/signupform') ?>
	</form>
	</div>
</div>