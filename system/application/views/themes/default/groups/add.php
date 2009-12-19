<div class="heading">
	<h2>Teams</h2>
</div>
<div id="content">
		<form class="grid_9 alpha" action="/groups/add" method="post" accept-charset="utf-8">
			<fieldset>
				<legend>Create New Team</legend>
				<p>
					<label for="name">Screenname</label>		
					<?php echo $form->input('name') ?>
					<span class="note">A team's screenname may only contain letters <em>(A-Za-z)</em>, numbers <em>(0-9)</em>, and underscores <em>(_)</em>.  No spaces allowed.</span>
					<?php echo $form->error('name') ?>
				</p>
				<p>
					<label for="name">Team Name</label>		
					<?php echo $form->input('fullname') ?>
					<span class="note">Enter your real team name, so people can recognize the team. </span>
					<?php echo $form->error('fullname') ?>		
				</p>
				<p class="submit">		
					<input type="submit" class="button" value="Create Team" />
				</p>	
				<div class="inlineMessage">
					<p class="text"><strong>Note:</strong> Once your team network is created, the admin (you) will be able to invite users via email invitation ONLY.<br/>
					Access to the team page and its updates are for invited members by the admin ONLY.<br/>
					To invite, click on the "Invite Members" tab on the team page.</p>
				</div>
			</fieldset>
		</form>
	
</div>