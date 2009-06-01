<div class="box messages">
	<div class="top short">
		<h3>Upload your avatar</h2>
	</div>
	<div class="box"><div class="box"><div class="box">
	<form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
	<p>
		<?php echo $form->input('avatar', array('type'=>'file')) ?>
	</p>
	<p>
		<input class="confirm button" type="submit" value="Upload">
	</p>
	</form>
	</div></div></div>
</div>