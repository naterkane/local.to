<?php if ((is_array($message)) AND ($message['message_html'] !== null)) :?>
	<?php 
	if (empty($sent)) 
	{
		$user = $this->User->getByUsername($message['username']);
	}
	else 
	{
		$user = $this->User->get($message['to']);
	}
	$message['email'] = $user['email'];
	/*?><!--
	<a href="/<?php echo $message['username']?>" class="image"><?php echo $avatar->user($message, "48" ); ?></a>
	<p class="author"><?php echo $html->link(!empty($message['realname'])?$message['realname']:$message['username'], '/' . $message['username']); ?></p>
	<p class="message-text"><?php echo $message['message_html'] ?></p>
	<p class="meta">
	<?php if (empty($message['to'])): ?>
		<?php echo $html->link($time_format->timeAgo($message['time']) . ' ago', '/' . $message['username'] . '/status/' . $message['id'], array('id'=>'messagelink' . $message['id']));?>		
		<?php if ($message['reply_to'] && empty($remove_reply_context)): ?>
			<span class="replyto">in reply to <a href="<?php echo '/' . $message['reply_to_username'] . '/status/' . $message['reply_to']; ?>"><?php echo (substr($message['reply_to_username'],-1) == "s")?$message['reply_to_username']."'":$message['reply_to_username']."'s"; ?> message</a></span> 
		<?php endif; ?>
		<?php if (empty($message['reply_to'])): ?>
			<?php if (count($message['replies']) > 0): ?>
				&mdash; <span id="reply_count<?php echo $message['id'] ?>">(<a href="<?php echo '/' . $message['username'] . '/status/' . $message['id'] ?>"><?php echo count($message['replies']); ?><?php echo (count($message['replies']) > 1)?"replies":"reply";?></a>)</span>
			<?php endif ?>
		<?php endif ?>
		<span class="reply" id="reply<?php echo $message['id'] ?>"><a href="/home/<?php echo (!empty($message['reply_to']))?$message['reply_to']:$message['id']; ?>">[Reply]</a></span>
	<?php else: ?>
		<?php echo $time_format->timeAgo($message['time']) . ' ago';?>				
	<?php endif ?>
	</p>-->
<?php */ ?>
<a href="/<?php echo $message['username']?>" class="thumb-med" title="<?php echo $message['username']?>"><?php echo $avatar->user($message, ($message['reply_to'] && empty($remove_reply_context))?"48":"36" ); ?></a>
<p><span class="author"><?php echo $message['username']?></span> <?php echo $message['message_html'] ?></p>
<span class="stamp">	
<?php if (empty($message['to'])): ?>
	<?php echo $html->link($time_format->timeAgo($message['time']) . ' ago', '/' . $message['username'] . '/status/' . $message['id'], array('id'=>'messagelink' . $message['id']));?>		
	<?php if ($message['reply_to'] && empty($remove_reply_context)): ?>
		<span class="replyto">in reply to <a href="<?php echo '/' . $message['reply_to_username'] . '/status/' . $message['reply_to']; ?>"><?php echo (substr($message['reply_to_username'],-1) == "s")?$message['reply_to_username']."'":$message['reply_to_username']."'s"; ?> message</a></span> 
	<?php endif; ?>
	<?php if (empty($message['reply_to'])): ?>
		<?php if (count($message['replies']) > 0): ?>
			&mdash; <span id="reply_count<?php echo $message['id'] ?>">(<a href="<?php echo '/' . $message['username'] . '/status/' . $message['id'] ?>"><?php echo count($message['replies']); ?><?php echo (count($message['replies']) > 1)?"replies":"reply";?></a>)</span>
		<?php endif ?>
	<?php endif ?>
	<ul class="post-actions">
		<li><a href="#" class="favorite">Mark Favorite</a></li>
		<li><a href="/home/<?php echo (!empty($message['reply_to']))?$message['reply_to']:$message['id']; ?>" class="reply-to">Reply to this Post</a></li>
	</ul>
</span>
<?php else: ?>
	<?php echo $time_format->timeAgo($message['time']) . ' ago';?>		
</span>		
<?php endif ?>


<?php endif ?>