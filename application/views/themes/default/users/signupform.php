<?php
/**
 * this file requires pwd_strength.js to be loaded into the document from whatever file is loading it.
 * 
 * <script type="text/javascript" src="/assets/js/pwd_strength.js"></script> 
 */
?>
			<fieldset class="login">
			<div class="grid_3">
			<p>
				<label for="username">Screenname</label>
				<?php echo $form->input('username') ?>
				<?php echo $form->error('username') ?>	
			</p>
			<p>
				<label for="realname">Full Name</label>
				<?php echo $form->input('realname') ?>
				<?php echo $form->error('realname') ?>	
			</p>	
			<p>
				<label for="email">Email</label>
				<?php echo $form->input('email') ?>
				<?php echo $form->error('email') ?>			
			</p>
			</div>
			<div class="grid_3">	
			<p>
				<label for="password">Password</label>
				<?php echo $form->input('password', array('type'=>'password','onkeyup'=>'runPassword(this.value, "password");')) ?>
				<?php echo $form->error('password') ?>
			</p>	
			<p>
				<label for="password">Password Confirm</label>
				<?php echo $form->input('passwordconfirm', array('type'=>'password')) ?>
				<span class="note" style="display: block; width: 190px;">
					<span id="password_text">Password Strength</span>
					<span id="password_bar" style="display: block; font-size: 1px; height: 2px; width: 0px; border: 1px solid white;"></span> 
				</span>
			</p>
				
			<p><input type="submit" class="button" value="Sign Up"></p>
			</div>
		</fieldset>