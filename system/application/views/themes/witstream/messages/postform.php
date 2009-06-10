<p>
	<?php echo $form->textarea('message', array('rows'=>3,'class'=>'grid_10','value'=>$message, 'id'=>'comment-box')); ?>
	<?php echo $form->error('message') ?>
	<?php echo $form->error('reply_to') ?>
	<?php echo $form->input('reply_to', array('type'=>'hidden')) ?>
	<?php echo $form->input('reply_to_username', array('type'=>'hidden')) ?>	
</p>
<p>
	<button id="update-btn" class="button" value="">Post</button>
</p>