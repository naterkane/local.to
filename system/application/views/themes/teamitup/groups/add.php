<div class="box">
	<h2>Groups</h2>
	<div class="block">
		<form action="/groups/add" method="post" accept-charset="utf-8">
			<fieldset>
				<legend>Add a Group</legend>
				<p>
					<label for="name">Name</label>		
					<?php echo $form->input('name') ?>
					<?php echo $form->error('name') ?>		
				</p>
				<p>		
					<input type="submit" value="Add">
				</p>	
			</fieldset>
		</form>
	</div>
</div>