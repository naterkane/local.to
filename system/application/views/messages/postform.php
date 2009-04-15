<h2>Hello <?php echo $User['username']  ?></h2>
<h3>What are you doing?</h3>
<form action="/messages/add" method="post" accept-charset="utf-8">
	<div class="mod">
		<?php echo $this->form->textarea('message', array('rows'=>8, "cols"=>40)); ?>
		<?php echo $this->form->error('message') ?>
	</div>
	<div class="mod">
		<input type="submit" value="Update">
	</div>
</form>