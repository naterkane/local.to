<div class="heading">
	<h2><?php echo $avatar->group($group, "48"); ?> <span><?php echo $html->groupName($group) ?></span></h2>
</div>
<div id="content">
	<?php $this->load->view("groups/subnav/group_nav"); ?>	
	<div class="content">
		<h3>Blacklist</h3>
<?php if ($blacklist): ?>
	<table border="0" cellspacing="5" cellpadding="5">
		<thead>
			<tr>
				<th>Name</th>
				<th>Remove</th>		
			</tr>
		</thead>
		<tbody>
		<?php foreach ($blacklist as $user): ?>
			<tr>
				<td><?php echo $html->name($user) ?></td>
				<td><?php echo $html->link('Remove', '/groups/unblacklist/' . $group['id'] . '/' . $user['id'], array('id'=>'unblacklist' . $user['username'],'class'=>'button'), 'Are you sure you want to remove the user from the blacklist?') ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<p class="message"><strong>There are currently no blacklisted members.</strong></p>
<?php endif; ?>
	</div>
</div>