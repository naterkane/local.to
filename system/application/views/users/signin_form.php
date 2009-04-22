<p>Don't have an account? <a href="/signup">Sign up</a>!</p>
<form action="/signin" method="post" accept-charset="utf-8">
	<div class="mod">
		<label for="username">User Name</label>
		<?php echo $this->form->input('username') ?>
	</div>
	<div class="mod">
		<label for="password">Password</label>		
		<?php echo $this->form->input('password', array('type'=>'password')) ?>
	</div>
	<div class="mod">
		<input type="submit" value="Sign In">
	</div>
</form>