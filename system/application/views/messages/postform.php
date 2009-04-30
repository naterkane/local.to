<div class="mod">
	<?php echo $form->textarea('message', array('rows'=>8, "cols"=>40)); ?>
	<?php echo $form->error('message') ?>
	<?php echo $form->input('reply_to', array('type'=>'hidden')) ?>
	<?php echo $form->input('reply_to_username', array('type'=>'hidden')) ?>	
</div>
<div class="mod">
	<input type="submit" value="Update">
</div>