<h2>Sign Up</h2>
<form action="/signup" method="post" accept-charset="utf-8">
	<div class="mod">
		<label for="username">User Name</label>
		<?php echo $form->input('username') ?>
		<?php echo $form->error('username') ?>	
	</div>
	<div class="mod">
		<label for="password">Password</label>
		<?php echo $form->input('password', array('type'=>'password')) ?>
		<?php echo $form->error('password') ?>			
	</div>	
	<div class="mod">
		<label for="password">Password Confirm</label>
		<?php echo $form->input('passwordconfirm', array('type'=>'password')) ?>
	</div>
	<div class="mod">
		<label for="email">Email</label>
		<?php echo $form->input('email') ?>
		<?php echo $form->error('email') ?>			
	</div>	
	<p><input type="submit" value="Sign Up"></p>
</form>