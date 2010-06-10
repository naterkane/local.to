<div class="heading">
	<h2>Settings</h2>
</div>
<div id="content">
	<?php $this->load->view('users/subnav/settings_nav');?>
	<form class="grid_9 alpha" action="/users/delete" method="post" accept-charset="utf-8">
		<fieldset>
			<legend>Delete my account</legend>
			<p class="submit">
				<?php echo $form->input('update_key', array('type'=>'hidden')) ?>
				<input class="button" type="submit" value="Yes, I'm sure. Just do it." onclick="return confirm('Are you sure you want to delete your account? This cannot be undone.'); event.returnValue = false; return false;" />
			</p>
		</fieldset>
	</form>
	<div class="clear"></div>
</div>