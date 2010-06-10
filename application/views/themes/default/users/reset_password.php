<div class="container_12 prefix_3">
	<div class="sign-up-form grid_6">
		<div class="description grid_3">
		<h3>Reset Password</h3>
		<p>Please change your password to something that's going to be difficult for others to guess, but you can easily remember.</p>
		</div>
		<div class="grid_3">
			<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" class="form" method="post" accept-charset="utf-8">
				<fieldset class="password">
					<p>
						<label for="password">New Password</label>
						<?php echo $form->input('new_password', array('type'=>'password','onkeyup'=>'runPassword(this.value, "password");')) ?>
						<?php echo $form->error('password') ?>
						<span class="note" style="display: block; width: 380px;">
							<span id="password_text">&nbsp;</span>
							<span id="password_bar" style="display: block; font-size: 1px; height: 2px; width: 0px; border: 1px solid white;"></span> 
						</span>
					</p>
					<p>
						<label for="passwordconfirm">Password Confirm</label>
						<?php echo $form->input('passwordconfirm', array('type'=>'password')) ?>
						<?php echo $form->error('passwordconfirm') ?>
					</p>
					<p><input class="confirm button" type="submit" value="Update"></p>
				</fieldset>
			</form>
			<script type="text/javascript" src="/assets/js/pwd_strength.js"></script> 
		</div>
	</div>
</div>