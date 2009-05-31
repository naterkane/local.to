<h2>Add an invite</h2>

<p>Separate multiple emails with commas, no line spaces.</p>

<form action="/groups/invites/<?php echo $group['name'] ?>" method="post">
	<fieldset>
		<p><?php echo $form->textarea('invites', array('rows'=>3,'class'=>'grid_10')); ?></p>
	</fieldset>
	<p><input class="confirm button" type="submit" value="Add Invites"></p>
</form>

<?php if (!empty($invite_list)): ?>
	<div id="invites_block">
	<h3>Pending Invites</h3>
	<table border="0" cellspacing="5" cellpadding="5" id="invites_table">
		<tr>
			<th>Sent to</th>
			<th>Key</th>
			<th>Date Sent</th>
			<th>Actions</th>			
		</tr>
		<?php $count = 0 ?>
		<?php foreach ($invite_list as $invite): ?>
			<tr>
				<td><?php echo $invite['email'] ?></td>
				<td><?php echo $invite['key'] ?><?php echo $form->input('count-' . $count, array('value'=>$invite['key'], 'type'=>'hidden')) ?></td>
				<td><?php echo date('j/n/y g:i a', $invite['created']) ?></td>
				<td><?php echo $html->link('Delete', '/groups/deleteinvite/' . $invite['key'] . $html->sendMeHere(), null, 'Are you sure you want to delete this invitation? This cannot be undone.') ?></td>
			</tr>
		<?php $count++ ?>			
		<?php endforeach ?>
	</table>	
	</div>
<?php endif ?>