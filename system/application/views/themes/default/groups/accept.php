<h3>Create an Account</h3>
<p class="neutral">You must be signed in to join a team. Already have an account? <?php echo $html->link('sign in', '/signin' . $html->sendMeHere()) ?></p>
<div class="sign-up-form grid_6">
	<form action="/groups/accept/<?php echo $key ?>" method="post" accept-charset="utf-8">
		<?php echo $this->load->view('users/signupform') ?>
	</form>
</div>
<script type="text/javascript" src="/assets/js/pwd_strength.js"></script> 