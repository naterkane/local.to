<?php if ($friend_select): ?>
	<form class="postform" action="/messages/add?redirect=/inbox" method="post" accept-charset="utf-8">
		<h3>Send <?php echo $form->select('to', $friend_select) ?> a private message</h3>		
		<fieldset>
		<?php echo $this->load->view('messages/postform') ?>
		</fieldset>
	</form>
	<?php
	if ($this->util->isSection("/inbox"))
	{
		?>
		<h3 class="tabs">Inbox</h3> <a href="/sent" class="toggler">Sent</a>
		<?php
	} 
	elseif ($this->util->isSection("/sent"))
	{
		?>
		<h3 class="tabs">Sent</h3> <a href="/inbox" class="toggler">Inbox</a>
		<?php
	}
	?>
<?php else: ?>
	<?php
	if ($this->util->isSection("/inbox"))
	{
		?>
		<h3 class="tabs">Inbox</h3> <a href="/sent" class="toggler">Sent</a>
		<?php
	} 
	elseif ($this->util->isSection("/sent"))
	{
		?>
		<h3 class="tabs">Sent</h3> <a href="/inbox" class="toggler">Inbox</a>
		<?php
	}
	?>
	</div>
	<div class="box content">
		
			<h3>Sorry, but nobody is following you.</h3>
			<h4>You can only send private messages to people if they are.</h4>
			<p>If you go and check out the <a href="/public_timeline">public timeline</a> and reply to others, maybe you can make new a friend or two!</p>
		
<?php endif ?>