<div class="container_12 prefix_3">
	<div class="sign-up-form grid_6">
		<div class="description grid_3">
			<h3>Recover Password</h3>
			<p>You can't remember your password? It's ok. Just enter the email address you registered with, and we'll get it sorted.</p>
		</div>
		<div class="grid_3">
			<form action="/recover_password" class="form" method="post" accept-charset="utf-8">
				<fieldset class="password">
				<br/><br/>
				<p>
					<label for="username">Email</label>
					<?php echo $form->input('email'); ?> 
				</p>
				<p>
					<input type="submit" class="button" value="Next Step"/>
				</p>
				</fieldset>
			</form>
		</div>
	</div>
</div>