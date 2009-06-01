<div class="block">
	<?php
	if (empty($redirect)) 
	{
		$redirect = '/';
	}
	?>
	<form action="/signin?redirect=<?php echo $redirect ?>" method="post" accept-charset="utf-8">
		<fieldset class="login">
		<!--<legend>Login Information</legend>-->
		<p>
			<label for="username">User Name</label>
			<?php echo $form->input('username') ?>
		</p>
		<p>
			<label for="password">Password</label>		
			<?php echo $form->input('password', array('type'=>'password')) ?>
		</p>
		<p>
			<input class="confirm button" type="submit" value="Sign In">
		</p>
		</fieldset>
	</form>	
	<p><a href="/recover_password">Forget your password?</a></p>
	<p>Don't have a login yet? <?php /*<a href="/signup">Sign up</a>*/ ?><a href="/request_invite">Request an Account</a>!</p>
</div>