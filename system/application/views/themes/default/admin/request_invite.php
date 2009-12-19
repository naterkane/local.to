<div class="sign-up-form grid_6">
	<div class="description grid_3">
	<h3>Sign Up</h3>
	<p><em>We're currently in private alpha.</em> If you'd like to participate in our public beta, please submit your email address and we'll send you an invitation as soon as accounts are available.</p>
	</div>
	<div class="grid_3">
	<form action="/admin/create_invite" method="post" accept-charset="utf-8">
			<fieldset class="login">
				<p>
					<label for="email">Email Address</label>
					<?php echo $form->input('email') ?>
					<?php echo $form->error('email') ?>			
				</p>	
				<p>
					<label for="emailconfirm">Email Confirm</label>
					<?php echo $form->input('emailconfirm') ?>
				</p>
				<p><input type="submit" class="button" value="Request Invite" id="signMeUp" /></p>
			</fieldset>
		</form>
	</div>
</div>