<div class="heading">
	<h2>Settings</h2>
</div>
<div id="content">
	<?php $this->load->view('users/subnav/settings_nav');?>
	<form class="grid_9 alpha" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
		<fieldset>
			<legend>Upload a New Picture</legend>
			<p class="indent">
				<?php 
					if (!is_array($avatarid)){
						$avatarid = $this->User->get($avatarid);
					}
					echo $avatar->$avatartype($avatarid, "48" ); ?> 
					<?php echo $avatar->$avatartype($avatarid, "36" ); ?> 
					<?php echo $avatar->$avatartype($avatarid, "24" ); ?>
			</p>
			<p>
				<label for="avatar">New Image</label>
				<?php echo $form->input('avatar', array('type'=>'file')) ?>
				<span class="note">Select an image file on your computer (200K max).<br/>
				By uploading a file you certify that you have the right to distribute this picture and that it does not violate the <a href="/terms">Terms of Service</a>.</span>
			</p>
			<p class="submit">
				<input class="button" type="submit" value="Upload"/>
			</p>
		</fieldset>
	</form>
	<div class="clear"></div>
</div>