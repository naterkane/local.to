<div class="box messages">
	<div class="box">
	<div class="block">
		<h3>Post a direct message to <?php echo $name ?></h3>
		<form action="/messages/add" method="post" accept-charset="utf-8">		
			<fieldset>		
				<?php $this->load->view('messages/postform.php') ?>
			</fieldset>
		</form>
	</div>
	</div>
	<?php echo $this->load->view('messages/viewlist') ?>
</div>