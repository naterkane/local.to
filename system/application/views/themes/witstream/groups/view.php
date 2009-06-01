<div class="box messages">
	<div class="top">
	<h2><?php echo $group['name'] ?> </h2>
			<?php
			if ($group['im_a_member']): ?>
			<form action="/messages/add" class="postform" method="post" accept-charset="utf-8">
			<h3>Post a message to <?php echo $group['name'] ?></h3>
				<fieldset>
				<?php 
				$messagedata = array();
				$messagedata['message'] = "!".$group['name']." ";
				echo $this->load->view('messages/postform',$messagedata) ?>
				</fieldset>
			</form>
			<?php endif; ?>

	</div>
	<?php echo $this->load->view('messages/viewlist',array('messages'=>$messages)); ?>
	<div class="box threading">
		<?php $this->load->view('users/toggle_threading',array('threading'=>$this->userData['threading'])); ?>
	</div>
</div>