<?php if (is_array($message) && !empty($message['message_html'])) :?>
	<?php 
	if (empty($sent) || empty($message['to'])) 
	{
		$user = $this->User->getByUsername($message['username']);
	}
	else 
	{
		$user = $this->User->get($message['to']);
	}
	$message['email'] = $user['email'];
	if(!empty($message['group'])):
		$message['group'] = $this->Group->getByName($message['group'][0]);
	endif; 
	?>
	<h2 class="message_text">
		<?php if (($message['dm'] == true) && (!empty($group))): ?>
			<em>PRIVATE</em> 
		<?php endif; ?>		
		<?php echo $message['message_html']; ?>
	</h2>
	<p class="meta">		
	<?php if (!$this->Message->isDeleted($message)): ?>
		<?php $time = $time_format->timeAgo($message['time'])  . " ago" ?>
		<?php if (!$message['dm']): ?>
			<?php echo $html->link($time, '/' . $message['User']['username'] . '/status/' . $message['id'], array('id'=>'messagelink' . $message['id'])); ?>
		<?php else: ?>
			<?php echo $time ?>							
		<?php endif; ?>
		<?php if ((!empty($message['reply_to_username'])) && (empty($message['isReply']))): ?>
			in reply to <?php echo " " . $html->link($message['reply_to_username'] . '\'s message', '/' . $message['reply_to_username'] . '/status/' . $message['reply_to']); ?>
		<?php endif; ?>
		<?php if (count($message['replies']) > 0): ?>
			<span id="reply_count_<?php echo $message['id']; ?>">
				&mdash;
				(<?php echo $html->link(count($message['replies']) . ' replies', '/' . $message['User']['username'] . '/status/' . $message['id'], array('id'=>'reply-link-' . $message['id'])); ?>)
			</span>
		<?php endif; ?>
	<?php endif; ?>
	</p>
	
	<div class="clear"></div>
			<div class="avatar">
			<?php if (!empty($message['reply_to']) && isset($remove_reply_context)): ?>
			<a href="/<?php echo $message['username']?>" class="from_av"><?php echo $avatar->user($message, "48" ); ?></a>
			<?php elseif (!empty($message['reply_to'])): ?>
			<a href="/<?php echo $message['username']?>" class="from_av"><?php echo $avatar->user($message, "36" ); ?></a>
			<a href="/<?php echo $message['username']?>" class="to_av"><?php echo $avatar->user($this->User->getByUsername($message['reply_to_username']), "24" ); ?></a>
			<?php elseif (!empty($message['group'])): ?>	
			<a href="/<?php echo $message['username']?>" class="from_av"><?php echo $avatar->user($message, "36" ); ?></a>
			<a href="/<?php echo $message['username']?>" class="to_av"><?php echo $avatar->group($message['group'], "24" ); ?></a>
			<?php else: ?>
			<a href="/<?php echo $message['username']?>"><?php echo $avatar->user($message, "48" ); ?></a>
			<?php endif; ?>
		</div>	
		<p class="author"><?php echo $html->link($message['User']['username'], '/' . $message['User']['username']); ?><br/><?php echo $html->name($message['User']); ?></p>
		<div class="post-actions">
		<?php if (!$this->Message->isDeleted($message) && $this->userData): ?>
			<span class="reply-to" id="reply_<?php echo $message['id']; ?>">
				<?php echo $html->replyLink($message, (!empty($dm))?$dm:null); ?>
			</span>
			<span class="favorite">
				<?php echo $html->favoriteLink($message, $this->userData); ?>
			</span>
			<span class="delete">
			<?php echo $html->deleteMessageLink($message, $this->Message->isOwner($message)); ?>
			</span>
		<?php endif; ?>
	</div>	
	<div class="clear"></div>
<?php endif; ?>