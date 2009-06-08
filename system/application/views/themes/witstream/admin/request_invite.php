<div class="box signup">
	<h2>Request an Account</h2>
	<div class="block">
	<form action="/admin/create_invite" method="post" accept-charset="utf-8">
		<fieldset class="login">
		<p>Please enter your email address twice just to make sure that you get it right.</p>
		<p>
			<label for="email">Email Address</label>
			<?php echo $form->input('email') ?>
			<?php echo $form->error('email') ?>			
		</p>	
		<p>
			<label for="emailconfirm">Confirm Email Address</label>
			<?php echo $form->input('emailconfirm') ?>
		</p>
		<p><input type="submit" class="button" value="I really want in!"></p>
		</fieldset>
	</form>
	</div>
</div>