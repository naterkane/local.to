<p>
	<?php
		$session_message = $this->cookie->get('message');
		if ($session_message) 
		{
			$message = $session_message;
			$this->cookie->remove('message');
		}
		else 
		{
			$message = null;
		}
	?>
	<?php echo $form->textarea('message', array('id'=>'comment-box','rows'=>3,'class'=>'grid_10','value'=>$message)); ?>
	<?php echo $form->error('message') ?>
	<?php echo $form->error('reply_to') ?>
	<?php 
		if (!empty($group['name'])) 
		{
			echo $form->input('group_name', array('type'=>'hidden', 'value'=>$group['name']));
		}
	?>
	<?php echo $form->input('reply_to', array('type'=>'hidden')) ?>
	<?php echo $form->input('reply_to_username', array('type'=>'hidden')) ?>	
</p>
<p>
	<input class="button" id="update-btn" type="submit" value="Update"/>
</p>