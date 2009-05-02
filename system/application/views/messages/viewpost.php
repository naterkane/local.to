<?php
if ((is_array($message)) AND (!empty($message['message_html'])))
{
?>
<!--
<span class="message_text"><?php echo $message['message_html'] ?></span>
<span class="author"><?php echo $html->link($message['username'], '/' . $message['username']); ?> </span>
<span class="meta"><?php echo $html->link($time_format->timeAgo($message['time']) . ' ago', '/' . $message['username'] . '/status/' . $message['id'], array('id'=>'messagelink' . $message['id']));?></span>
<?php if (empty($message['reply_to'])): ?>
	<p>
		<span class="reply" id="reply<?php echo $message['id'] ?>"><a href="/home/<?php echo $message['id'] ?>">[Reply]</a></span>
		<?php if ($message['reply_count'] > 0): ?>
			<span id="reply_count<?php echo $message['id'] ?>">
				<a href="<?php echo '/' . $message['username'] . '/status/' . $message['id'] ?>">
				<?php echo $message['reply_count'] ?>
				<?php if ($message['reply_count'] > 1): ?>
					(s) replies
				<?php else: ?>
					reply
				<?php endif ?>
				</a>
			</span>
		<?php endif ?>
	</p>
<?php else: ?>
	<?php if (empty($remove_reply_context)): ?>
		<p><span class="replyto">In reply to <a href="<?php echo '/' . $message['reply_to_username'] . '/status/' . $message['reply_to'] ?>">this message</a></span></p>
	<?php endif ?>
<?php endif ?>
-->


<!-- -->
	<a href="#" class="image">
		<img src="img/photo_60x60.jpg" width="60" height="60" alt="photo" />
	</a>
	<p class="author"><?php echo $html->link(($User['realname'])?$User['realname']:$User['username'], '/' . $message['username']); ?> </p>
	<p class="message_text"><?php echo $message['message_html'] ?></p>

	<p class="meta">		
	
		<?php if ($message['reply_to'] && empty($remove_reply_context)): ?>
		<span class="replyto">In reply to <a href="<?php echo '/' . $message['reply_to_username'] . '/status/' . $message['reply_to'] ?>">this message</a></span>
		<?php endif; ?>
		<?php /* if (empty($remove_reply_context)): ?>
		<span class="replyto">In reply to <a href="<?php echo '/' . $message['reply_to_username'] . '/status/' . $message['reply_to'] ?>">this message</a></span>
		<?php endif */ ?>
		<?php echo $html->link($time_format->timeAgo($message['time']) . ' ago', '/' . $message['username'] . '/status/' . $message['id'], array('id'=>'messagelink' . $message['id']));?>
		<?php if (empty($message['reply_to'])): ?>
			<?php if ($message['reply_count'] > 0): ?>
				<span id="reply_count<?php echo $message['id'] ?>">
					<a href="<?php echo '/' . $message['username'] . '/status/' . $message['id'] ?>">
					<?php echo $message['reply_count']; ?>
					<?php echo ($message['reply_count'] > 1)?"replies":"reply";?>
					</a>
				</span>
			<?php endif ?>
		<?php else: ?>
		<span class="reply" id="reply<?php echo $message['id'] ?>"><a href="/home/<?php echo $message['id'] ?>">[Reply]</a></span>
		<?php endif ?>
	</p>


<?php } ?>