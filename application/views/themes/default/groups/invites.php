<div class="heading">
	<h2><?php echo $avatar->group($group, "48"); ?> <span><?php echo $html->groupName($group) ?></span></h2>
</div>
<div id="content">
<?php $this->load->view("groups/subnav/group_nav"); ?>
	<form class="grid_9 alpha" action="/groups/invites/<?php echo $group['name']; ?>" method="post">
		<fieldset>
			<legend>Invite users to <?php echo $group['name']; ?></legend>
			<p class="text">To ensure <?php echo $this->config->item('group') ?> privacy, only those invited via email by the <?php echo $this->config->item('group') ?> admin will be able to access the <?php echo $this->config->item('group') ?> page and view the updates. Please enter the emails of users you would like to grant access to this <?php echo $this->config->item('group') ?>.</p>
			<p><span class="note">Separate multiple emails with commas, no line spaces.</span></p>
			<p class="indent"><?php echo $form->textarea('invites'); ?></p>
			<p class="submit"><input class="confirm button" type="submit" value="Create Invitations" /></p>
		</fieldset>
	</form>
	<div class="clearfix"></div>
<?php if (!empty($invite_list)): ?>
	<div class="content">
		<table border="0" cellspacing="5" cellpadding="5" id="invites_table">
			<colgroup>
				<col class="col40" />
				<col class="col20" />
				<col class="col20" />
				<col class="col20" />
			</colgroup>
			<thead>
				<tr>
					<th colspan="4" class="table-head">Pending Invites</th>
				</tr>
				<tr>
					<th>Sent to</th>
					<th>Invitation Key</th>
					<th>Date Sent</th>
					<th>Actions</th>
				</tr>
			</thead>
			<?php $count = 0; ?>
			<tbody>
			<?php foreach ($invite_list as $invite):

			?>
				<tr<?php echo (is_float(($count+1)/2))? ' class="odd"' : ""; ?>>
					<td><a href="mailto:<?php echo $invite['email'] ?>?subject=RE: Invite to join <?php echo $group['name'] ?> on <?php echo $this->config->item("service_name"); ?>"><?php echo $invite['email'] ?></a></td>
					<td><a href="<?php echo $this->config->item('base_url')?>groups/accept/<?php echo $invite['key']; ?>"><?php echo $invite['key'] ?></a><?php echo $form->input('count-' . $count, array('value'=>$invite['key'], 'type'=>'hidden')) ?></td>
					<td><?php echo date('n/j/y g:i a', $invite['created']) ?></td>
					<td><?php echo $html->link('Delete', '/groups/deleteinvite/' . $invite['key'] . $html->sendMeHere(), array('class'=>'toggler'), 'Are you sure you want to delete this invitation? This cannot be undone.') ?></td>
				</tr>
			<?php $count++; ?>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endif; ?>
</div>