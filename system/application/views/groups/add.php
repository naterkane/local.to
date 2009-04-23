<h2>Add a Group</h2>
<form action="/groups/add" method="post" accept-charset="utf-8">
<div class="mod">
	<div class="mod">
		<label for="name">Name</label>		
		<?php echo $this->form->input('name') ?>
		<?php echo $this->form->error('name') ?>		
	</div>
	<div class="mod">		
		<input type="submit" value="Add">
	</div>	
</div>