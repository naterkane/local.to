<h2>Recover Password</h2>
<form action="/recover_password" method="post" accept-charset="utf-8">
	<fieldset class="password">
	<p>
		<label for="username">Email</label>
		<?php echo $form->input('email') ?> <input type="submit" value="Next Step">
	</p>
	</fieldset>
</form>