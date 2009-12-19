<h3>Create your Account</h3>
<div class="sign-up-form grid_6">
		<form action="/signup/<?php echo $invite_key ?>" method="post" accept-charset="utf-8">
			<?php echo $this->load->view('users/signupform') ?>
		</form>
		<script type="text/javascript" src="/assets/js/pwd_strength.js"></script> 
</div>