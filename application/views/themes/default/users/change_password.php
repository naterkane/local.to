<div class="heading">
	<h2>Settings</h2>
</div>
<div id="content">
	<?php $this->load->view('users/subnav/settings_nav');?>
	<form class="grid_10 alpha" action="/change_password" method="post" accept-charset="utf-8">
		<fieldset class="login">
			<legend>Change Password</legend>
			<p>
				<label for="password">Current Password</label>
				<?php echo $form->input('password', array('type'=>'password')) ?>
				<?php echo $form->error('old_password') ?>
				<span class="note">Please enter your current password.</span>
			</p>	
			<p>
				<label for="password">New Password</label>
				<?php echo $form->input('new_password', array('type'=>'password','onkeyup'=>'runPassword(this.value, "new_password");')) ?>
				<span class="note" style="display: block; width: 380px;">
					<span id="new_password_text">Strength</span>
					<span id="new_password_bar" style="display: block; font-size: 1px; height: 2px; width: 0px; border: 1px solid white;"></span> 
				</span>
				<?php echo $form->error('password') ?>
				<?php echo $form->error('password_confirm') ?>
			</p>
			<p>
				<label for="password">Password Confirm</label>
				<?php echo $form->input('new_password_confirm', array('type'=>'password')) ?>
				<?php echo $form->error('new_password_confirm') ?>	
				<span class="note">Please enter your new password a second time.</span>	
			</p>
			<p class="submit"><input class="button" type="submit" value="Update"></p>
		</fieldset>
	</form>
	<script type="text/javascript" src="/assets/js/pwd_strength.js"></script> 
	<div class="clear"></div>
</div>