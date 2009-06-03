<h2>SMS</h2>
<?php if ($sms_pending): ?>
	<p>A code has been sent to <?php echo $User['phone'] ?>. When you receive this code please type it into the field below and click 'activate' to activate this device. You can also <?php echo $html->link('cancel and enter new phone number', '/settings/sms?cancel') ?>.</p>
	<form class="grid_9 alpha" action="/settings/sms" method="post" accept-charset="utf-8">
		<p>
			<label for="key"></label>	
			<?php echo $form->input('key') ?>
			<?php echo $form->error('key') ?>
		</p>
		<p><input class="confirm button" type="submit" value="Activate"></p>
	</form>
<?php else: ?>
	<form class="grid_9 alpha" action="/settings/sms" method="post" accept-charset="utf-8">
		<p>
			<label for="device_updates">Device Updates</label>	
			<?php echo $form->checkbox('device_updates'); ?>
		</p>
		<p>
			<label for="carrier">Carriers</label>	
			<?php echo $form->select('carrier', $carriers) ?>
			<?php echo $form->error('carrier') ?>		
		</p>
		<p>
			<label for="phone">Phone</label>	
			<?php echo $form->input('phone'); ?>
			<br />Please enter numbers only.
			<?php echo $form->error('phone') ?>					
		</p>
		<p><input class="confirm button" type="submit" value="Update"></p>								
	</form>
<?php endif ?>