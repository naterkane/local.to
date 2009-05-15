<?php
if ((is_array($message)) AND (!empty($message['message_html'])))
{
	echo $username;
	echo $user;
	//if (!empty($username) && $username != $message['username']){
		?><a href="/<?php echo $message['username']?>" class="image"><?php 
		$author = $this->User->getByUsername($message['username']);
		echo $gravatar->img( $author['email'],"60" ); 
		?></a>
	<p class="author"><?php echo $html->link(!empty($message['realname'])?$message['realname']:$message['username'], '/' . $message['username']); ?> </p>
	<?php 
	//} ?>
	<p class="message_text"><?php echo $message['message_html'] ?></p>
	<p class="meta">
		<?php echo $html->link($time_format->timeAgo($message['time']) . ' ago', '/' . $message['username'] . '/status/' . $message['id'], array('id'=>'messagelink' . $message['id']));?>		
		<?php if ($message['reply_to'] && empty($remove_reply_context)): ?>
		<span class="replyto">in reply to <a href="<?php 
					echo '/' . $message['reply_to_username'] . '/status/' . $message['reply_to']; 
					?>"><?php 
					echo (substr($message['reply_to_username'],-1) == "s")?$message['reply_to_username']."'":$message['reply_to_username']."'s";
					?> message</a></span> 
		<?php endif; ?>
		
		<?php if (empty($message['reply_to'])): ?>
			<?php if ($message['reply_count'] > 0): ?>
				&mdash; <span id="reply_count<?php echo $message['id'] ?>">
					(<a href="<?php echo '/' . $message['username'] . '/status/' . $message['id'] ?>">
					<?php echo $message['reply_count']; ?>
					<?php echo ($message['reply_count'] > 1)?"replies":"reply";?>
					</a>)
				</span>
			<?php endif ?>
		<?php endif ?>
		<span class="reply" id="reply<?php echo $message['id'] ?>"><a href="/home/<?php echo (!empty($message['reply_to']))?$message['reply_to']:$message['id']; ?>">[Reply]</a></span>
	</p>
	<div class="clear"></div>
<?php } ?>