<h2>Add an invite</h2>

<p>Separate multiple emails with commas, no line spaces.</p>

<form action="/groups/invites/<?php echo $group['name'] ?>" method="post">
	<fieldset>
		<p><?php echo $form->textarea('invites', array('rows'=>3,'class'=>'grid_10')); ?></p>
	</fieldset>
	<p><input class="confirm button" type="submit" value="Add Invites"></p>
</form>

<?php if (!empty($invite_list)): ?>
	<div id="invites">
	<h3>Pending Invites</h3>
	<table border="0" cellspacing="5" cellpadding="5">
		<tr>
			<th>Sent to</th>
			<th>Key</th>
			<th>Date Sent</th>
			<th>Actions</th>			
		</tr>
		<?php foreach ($invite_list as $invite): ?>
			<tr id="id-<?php echo $invite['key'] ?>">
				<td id="email-<?php echo $invite['key'] ?>"><?php echo $invite['email'] ?></td>
				<td id="key-<?php echo $invite['key'] ?>"><?php echo $invite['key'] ?></td>
				<td id="sent-<?php echo $invite['key'] ?>"><?php echo date('j/n/y g:i a', $invite['created']) ?></td>
				<td id="delete-<?php echo $invite['key'] ?>"><?php echo $html->link('Delete', '/groups/deleteinvite/' . $invite['key'] . $html->sendMeHere(), null, 'Are you sure you want to delete this invitation? This cannot be undone.') ?></td>
			</tr>
		<?php endforeach ?>
	</table>	
	</div>
<?php endif ?>