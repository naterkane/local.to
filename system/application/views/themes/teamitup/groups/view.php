<div class="form-share">
	<?php if ($group['im_a_member']): ?>
          <span class="character-count" id="character-count"></span>
          <form action="/messages/add<?php echo $html->sendMeHere() ?>" class="form" method="post" accept-charset="utf-8">
          	<fieldset>
              <label for="message">Share something with <?php echo $group['name'] ?></label>
				</fieldset>
				<fieldset>
				<?php 
				$messagedata = array();
				$messagedata['message'] = "!".$group['name']." ";
				echo $this->load->view('messages/postform',$messagedata) ?>
				</fieldset>
			</form>
			<?php else: ?>
			<h2><?php echo $group['name'] ?></h2>
			<?php endif; ?>
	</div>
	<?php echo $this->load->view('messages/viewlist',array('messages'=>$messages)); ?>
	<div class="threading">
		<?php $this->load->view('users/toggle_threading',array('threading'=>$this->userData['threading'])); ?>
	</div>
</div>