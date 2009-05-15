<p>
	<?php echo $form->textarea('message', array('rows'=>3,'class'=>'grid_10','value'=>$message)); ?>
	<?php echo $form->error('message') ?>
	<?php echo $form->error('reply_to') ?>	
	<?php echo $form->input('reply_to', array('type'=>'hidden')) ?>
	<?php echo $form->input('reply_to_username', array('type'=>'hidden')) ?>	
</p>
<p>
	<input class="confirm button" type="submit" value="Update">
</p>