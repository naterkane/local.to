<div class="box">
	<h2>Recover Password</h2>
	<div class="block">
		<form action="/recover_password" method="post" accept-charset="utf-8">
			<fieldset class="password">
			<p>
				<label for="username">Email</label>
				<?php echo $form->input('email') ?> 
				<input type="submit" value="Next Step" />
			</p>
			</fieldset>
		</form>
	</div>
</div>