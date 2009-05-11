<div class="box">
	<h2>Sign In</h2>
	<div class="block">
	<form action="/signup/<?php echo $invite_email ?>/<?php echo $invite_key ?>" method="post" accept-charset="utf-8">
		<fieldset>
		<p>
			<label for="username">User Name</label>
			<?php echo $form->input('username') ?>
			<?php echo $form->error('username') ?>	
		</p>
		<p>
			<label for="password">Password</label>
			<?php echo $form->input('password', array('type'=>'password')) ?>
			<?php echo $form->error('password') ?>			
		</p>	
		<p>
			<label for="password">Password Confirm</label>
			<?php echo $form->input('passwordconfirm', array('type'=>'password')) ?>
		</p>
		<p>
			<label for="email">Email</label>
			<?php echo $form->input('email') ?>
			<?php echo $form->error('email') ?>			
		</p>	
		<p><input type="submit" value="Sign Up"></p>
		</fieldset>
	</form>
	</div>
</div>