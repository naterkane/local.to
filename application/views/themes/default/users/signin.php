<div class="container_12 prefix_3">
	<div class="sign-up-form grid_6">
		<div class="description grid_3">
		<h3>Sign In</h3>
		<p><em>We're currently in private alpha.</em> If you already have an account, please sign in.</p> 	
		<?php if ($this->testingData['testing']): ?>
		<p>Don't have a login yet? <a href="/request_invite">Request an Account</a>!</p>
		<?php endif ?>
		</div>
		<div class="grid_3">
		<?php echo $this->load->view('users/signin_form') ?>
		</div>
	</div>
</div>