  			  <?php echo $form->textarea('message', array('id'=>'comment-box','value'=>$message)); ?>
				<?php echo $form->error('message') ?>
				<?php echo $form->error('reply_to') ?>
				<?php echo $form->input('reply_to', array('type'=>'hidden')) ?>
				<?php echo $form->input('reply_to_username', array('type'=>'hidden')) ?>          
            </fieldset>
            <fieldset>
              <button id="update-btn" class="btn post-it">Update</button>