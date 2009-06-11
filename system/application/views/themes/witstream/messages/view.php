<div class="box messages">
	<div class="top">
		<div class="message">
		<?php //var_dump($message); ?>
		<p class="message-text"><?php echo $message['message_html'] ?></p>
		<a href="/<?php echo $message['username']?>" class="image"><?php echo $avatar->user($message, "50" ); ?></a>
		<p class="author"><?php echo $html->link(!empty($message['realname'])?$message['realname']:$message['username'], '/' . $message['username']); ?></p>
		
		<p class="meta">
		<?php if (empty($message['to'])): ?>
			<?php echo $html->link($time_format->timeAgo($message['time']) . ' ago', '/' . $message['username'] . '/status/' . $message['id'], array('id'=>'messagelink' . $message['id']));?>		
			<?php if ($message['reply_to'] && empty($remove_reply_context)): ?>
				<span class="replyto">in reply to <a href="<?php echo '/' . $message['reply_to_username'] . '/status/' . $message['reply_to']; ?>"><?php echo (substr($message['reply_to_username'],-1) == "s")?$message['reply_to_username']."'":$message['reply_to_username']."'s"; ?> message</a></span> 
			<?php endif; ?>
			<?php if (empty($message['reply_to'])): ?>
				<?php if (count($message['replies']) > 0): ?>
					&mdash; <span id="reply_count<?php echo $message['id'] ?>">(<a href="<?php echo '/' . $message['username'] . '/status/' . $message['id'] ?>"><?php echo count($message['replies']); ?><?php echo (count($message['replies']) > 1)?"replies":"reply";?></a>)</span>
				<?php endif; ?>
			<?php endif; ?>
			<?php if (!empty($User)): ?>
				<span class="reply" id="reply<?php echo $message['id'] ?>"><a href="/home/<?php echo (!empty($message['reply_to']))?$message['reply_to']:$message['id']; ?>">[Reply]</a></span>
				<span class="favorite" id="favorite<?php echo $message['id'] ?>"><?php echo $html->favorite($message, $User) ?></span>
			<?php endif; ?>
		<?php else: ?>
			<?php echo $time_format->timeAgo($message['time']) . ' ago';?>				
		<?php endif ?>
		</p>
		<?php //$this->load->view('messages/viewpost', array('message'=>$message)); ?>
		</div>
	</div>
<?php if (!empty($messages)): ?>
	<div class="box content">
		<h4>Replies:</h4>
		<?php foreach ($messages as $message): ?>
		<div id="status-message-<?php echo $message['id'] ?>" class="message">
			<?php $this->load->view('messages/viewpost', array('message'=>$message,'remove_reply_context'=>true)); ?>
		</div>
			<?php //$this->load->view('messages/viewlist', array('messages'=>$messages,'username'=>$username,'remove_reply_context'=>true)); ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
</div>