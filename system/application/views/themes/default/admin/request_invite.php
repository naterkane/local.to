<div class="box">
	<h2>Request an invitation</h2>
	<div class="block">
	<form action="/admin/create_invite" method="post" accept-charset="utf-8">
		<fieldset class="login">
		<p>
			<label for="email">Email Address</label>
			<?php echo $form->input('email') ?>
			<?php echo $form->error('email') ?>			
		</p>	
		<p>
			<label for="emailconfirm">Email Confirm</label>
			<?php echo $form->input('emailconfirm') ?>
		</p>
		<p><input type="submit" class="button" value="I really want in!" id="signMeUp"></p>
		</fieldset>
	</form>
	</div>
</div>