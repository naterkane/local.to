<div class="box">
	<h2>Reset Password</h2>
	<div class="block">
		<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" accept-charset="utf-8">
			<fieldset class="login">
				<legend>Change Password</legend>
				<p>
					<label for="password">New Password</label>
					<?php echo $form->input('password', array('type'=>'password')) ?>
					<?php echo $form->error('password') ?>
					<?php echo $form->error('passwordconfirm') ?>
				</p>
				<p>
					<label for="password">Password Confirm</label>
					<?php echo $form->input('passwordconfirm', array('type'=>'password')) ?>
				</p>
				<p><input class="confirm button" type="submit" value="Update"></p>
			</fieldset>
		</form>
	</div>
</div>