<div class="settings">
	<div class="top short">
	<h3>Recover Password</h3>
	</div>
	<div class="box">
	<form action="/recover_password" method="post" accept-charset="utf-8">
		<fieldset class="password">
		<p>
			<label for="email">Please enter your email address</label>
			<?php echo $form->input('email') ?> 
		</p>
		<p><input type="submit" class="button" value="Next Step" /></p>
		</fieldset>
	</form>
</div></div>