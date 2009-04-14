<h2>Sign Up</h2>
<form action="/signup" method="post" accept-charset="utf-8">
	<div class="mod">
		<label for="username">User Name</label>
		<?php echo $this->form->input('username') ?>
		<?php echo $this->form->error('username') ?>	
	</div>
	<div class="mod">
		<label for="password">Password</label>
		<?php echo $this->form->input('password', array('type'=>'password')) ?>
		<?php echo $this->form->error('password') ?>			
	</div>
	<div class="mod">
		<label for="password">Password Confirm</label>
		<?php echo $this->form->input('passwordconfirm', array('type'=>'password')) ?>
	</div>
	<p><input type="submit" value="Sign Up"></p>
</form>