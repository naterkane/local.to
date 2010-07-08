<div class="heading">
	<h2>Settings</h2>
</div>
<div id="content">
	<?php $this->load->view('users/subnav/settings_nav');?>
	<form class="grid_11 alpha form" action="/settings/sms" method="post" accept-charset="utf-8">
			<fieldset>
				<legend>Email Notifications</legend>
				<p class="checkbox">
				<?php echo $form->checkbox('email_updates'); ?><label for="email_updates">Send private messages notifications to me via email</label>	
				</p>	
			</fieldset>
	<?php if ($sms_pending): ?>
			<fieldset>
				<legend>Please activate your Mobile device</legend>
				<p>A code has been sent to <?php echo $User['phone'] ?>. 
					When you receive this code please type it into the field below and click 'activate' to activate this device. 
					You can also <?php echo $html->link('cancel and enter new phone number', '/settings/sms?cancel') ?>.
				</p>
				<p>
					<label for="key">Secret key</label>	
					<?php echo $form->input('key') ?>
					<?php echo $form->error('key') ?>
				</p>
				<p class="submit"><input class="button" type="submit" value="Activate" /></p>
			</fieldset>
	<?php else: ?>
			<fieldset>
				<legend>Your Mobile Device Settings</legend>
				<div class="inlineMessage">
					<p><?php echo $this->config->item('service_name'); ?> doesn't charge anything for this, but standard text messaging rates or bundles may apply from your carrier.</p>
					<p>If you use your mobile in the United States, the United Kingdom, Canada, India or New Zealand you can receive Private Messages via SMS. If you would like to disable this feature in the future, you will have to return to this page to do so.</p>
				</div>
				<p class="checkbox">
					<?php echo $form->checkbox('device_updates'); ?><label for="device_updates">Enable private messages notifications of my mobile device</label>	
					<span class="note">Enabling your device settings allows you to receive private messages from your followers &amp; <?php echo $this->config->item('group')?>s via text message.</span>
				</p>
				<p>
					<label for="carrier">Carriers</label>	
					<?php echo $form->select('carrier', $carriers) ?>
					<?php echo $form->error('carrier') ?>		
				</p>
				<p>
					<label for="phone">Phone</label>	
					<?php echo $form->input('phone'); ?>
					<?php echo $form->error('phone') ?>		
					<span class="note">Please enter numbers only.</span>			
				</p>
				
				<p class="submit"><input class="button" type="submit" value="Update" /></p>
			</fieldset>
	<?php endif; ?>
	</form>
	<div class="clear"></div>
</div>