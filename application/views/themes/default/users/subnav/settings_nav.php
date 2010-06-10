	<ul class="subnav">
		<li<?php if (!$this->util->isSection("sms") &&
					!$this->util->isSection("avatar") &&
					!$this->util->isSection("change_password") &&
					!$this->util->isSection("delete_account") &&
					!$this->util->isSection("accounts")) { ?> class="current"<?php } ?>><?php echo $html->link('Profile Settings', '/settings') ?></li>
		<li<?php if ($this->util->isSection("/settings/accounts")) { ?> class="current"<?php } ?>><?php echo $html->link('Linked accounts', '/settings/accounts') ?></li>
		<li<?php if ($this->util->isSection("/settings/sms")) { ?> class="current"<?php } ?>><?php echo $html->link('Notification settings', '/settings/sms') ?></li>
		<li<?php if ($this->util->isSection("/settings/avatar")) { ?> class="current"<?php } ?>><?php echo $html->link('Add/Edit Picture', '/settings/avatar') ?></li>
		<li<?php if ($this->util->isSection("/change_password")) { ?> class="current"<?php } ?>><?php echo $html->link('Change Password', '/change_password') ?></li>
		<li<?php if ($this->util->isSection("/delete_account")) { ?> class="current"<?php } ?>><?php echo $html->link('Delete Account', '/delete_account') ?></li>
	</ul>