<div class="heading">
	<h2><?php echo ucfirst($this->config->item('group'))?>s</h2>
</div>
<div id="content">
		<form class="grid_9 alpha" action="/groups/add" method="post" accept-charset="utf-8">
			<fieldset>
				<legend>Create New <?php echo ucfirst($this->config->item('group'))?></legend>
				<p>
					<label for="name"><?php echo ucfirst($this->config->item('group'))?> Screenname</label>		
					<?php echo $form->input('name') ?>
					<span class="note">A team's screenname may only contain letters <em>(A-Za-z)</em>, numbers <em>(0-9)</em>, and underscores <em>(_)</em>.  No spaces allowed.</span>
					<?php echo $form->error('name') ?>
				</p>
				<p>
					<label for="name"><?php echo ucfirst($this->config->item('group'))?> Long Name</label>		
					<?php echo $form->input('fullname') ?>
					<span class="note"><strong>Tip:</strong> Enter your real <?php echo $this->config->item('group')?> name, so people can recognize the <?php echo $this->config->item('group')?>.</span>
					<?php echo $form->error('fullname') ?>		
				</p>
				<p class="submit">		
					<input type="submit" class="button" value="Create <?php echo ucfirst($this->config->item('group'))?>" />
				</p>	
				<div class="inlineMessage">
					<p class="text"><strong>Note:</strong> Once your <?php echo $this->config->item('group')?> network is created, the admin (you) will be able to invite users via email invitation.
					Access to the <?php echo $this->config->item('group')?> page and its updates is only available to members of the <?php echo $this->config->item('group')?>.p>
				</div>
			</fieldset>
		</form>
	
</div>