<?php
if (empty($dm)) 
{
	$dm = false;
}
if (empty($group_page)) 
{
	$group_page = false;
}
if (empty($group)) 
{
	$group = array();
}
?>
		<div class="block clearfix" id="messages">
		<?php if ($messages): ?>
			<?php foreach ($messages as $message): 
			
				// don't display private messages that have been deleted
				/**
				 * don't display private messages that have been deleted
				 * @todo abstract the rules to hide a message by in it's own method
				 */
				if ($dm == true && (!empty($message['deleted_by_user']) || !empty($message['deleted_by_admin']))){
					continue;
				}
			?>
				<div id="status-message-<?php echo $message['id'] ?>" class="message<?php 
				if (!empty($view_user)){ echo " no-avatar"; }
				if (!empty($message['isReply'])) { echo " reply"; } ?>">
					<?php if (empty($view_user)): ?>
					
					<div class="avatar">
						<?php 
						$avatarsize = (!empty($message['isReply'])) ? "36" : "48";
						$a = $avatar->user($message['User'], $avatarsize);
						//echo $html->link($a, '/' . $message['User']['username'], null, null, false); 
						//echo "<pre><code>".var_dump($message)."</code></pre>";
						if ($message['dm'] === true && !empty($sent) && empty($message['dm_group']) && !empty($message['to'])):
							
							$to = $this->User->get($message['to']);
						
						?>
						<a href="/<?php echo $to['username']; ?>"><?php echo $avatar->user($to,"48"); ?></a>
						<?php 
						elseif (!empty($sent) && !empty($message['dm_group'])): ?>
						<a href="/group/<?php echo $message['group_name']; ?>"><?php echo $avatar->group($this->Group->getByName($message['group_name']),"48"); ?></a>
						<?php elseif (!empty($message['reply_to']) && isset($remove_reply_context)): ?>
						<a href="/<?php echo $message['User']['username']?>" class="from_av"><?php echo $avatar->user($message, "48" ); ?></a>
						<?php elseif (!empty($message['reply_to'])): ?>
						<a href="/<?php echo $message['User']['username']?>" class="from_av"><?php echo $avatar->user($message, "36" ); ?></a>
							<?php if (empty($message['isReply'])): ?>
							<a href="/<?php echo $message['reply_to_username']?>" class="to_av"><?php echo $avatar->user($this->User->getByUsername($message['reply_to_username']), "24" ); ?></a>
							<?php /*elseif (!empty($message['group_name']) && !empty($group_page)): ?>	
							b<a href="/group/<?php echo $message['group_name']?>" class="to_av"><?php echo $avatar->group($this->Group->getByName($message['group_name']), "24" ); ?></a>
							<?php */endif; ?>
						<?php elseif (!empty($message['group_name']) && empty($group_page)):?>
						<a href="/<?php echo $message['User']['username']?>" class="from_av"><?php echo $avatar->user($message, "36" ); ?></a>
						<a href="/group/<?php echo $message['group_name']?>" class="to_av"><?php echo $avatar->group($this->Group->getByName($message['group_name']), "24" ); ?></a>
						<?php else: ?>
						<a href="/<?php echo $message['User']['username']?>"><?php echo $avatar->user($message, "48" ); ?></a>
						<?php endif; ?>
					</div>
					<?php endif; ?>
					<p>
						<?php $name = $html->name($message['User']);
						if (!empty($message['dm_group']) || (empty($view_user) && !isset($to))): 
						?>
							<span class="author<?php 
							if ($message['User']['locked'] == true) { 
								echo " private";
							}
							?>"><?php echo $html->link($name, '/' . $message['User']['username']) ?></span>
							<?php 
						elseif (!empty($to) && empty($message['dm_group'])):
						?>
							<span class="author<?php 
							if ($to['locked'] == true) { 
								echo " private";
							}
							?>"><?php echo $html->link($to['realname'].':', '/' . $to['username']) ?></span>
							<?php 	
						endif;
						
						
							if ($message['group_name'] && empty($group_page)): ?>
								<?php $group = $this->Group->getByName($message['group_name']); ?>
								<?php if ($message['dm_group']): ?>
									<em>sent to members of</em> 
								<?php else: ?>
									<em>posted to</em> 
								<?php endif ?>
								<span class="context"><?php echo $html->link($html->groupName($group), '/group/' . $message['group_name']) ?></span>:
							<?php 
							endif; 
						?>
						<span class="message_text" id="message-text-<?php echo $message['id'] ?>"><?php echo $message['message_html'] ?></span>
					</p>
					<p class="meta">
						<?php $time = $time_format->timeAgo($message['time'])  . " ago" ?>
						<?php if (!$message['dm']): ?>
							<?php echo $html->link($time, '/' . $message['User']['username'] . '/status/' . $message['id'], array('id'=>'messagelink' . $message['id'])) ?>
						<?php else: ?>
							<?php echo $time ?>							
						<?php endif ?>
						<?php if ((!empty($message['reply_to_username'])) && (empty($message['isReply']))): ?>
							in reply to <?php echo " " . $html->link($message['reply_to_username'] . '\'s message', '/' . $message['reply_to_username'] . '/status/' . $message['reply_to']) ?>
						<?php endif; ?>
						<?php if (count($message['replies']) > 0): ?>
							<span id="reply_count_<?php echo $message['id'] ?>">
								&mdash;
								(<?php echo $html->link(count($message['replies']) . ' replies', '/' . $message['User']['username'] . '/status/' . $message['id'], array('id'=>'reply-link-' . $message['id'])) ?>)
							</span>
						<?php endif; ?>
					</p>
					<div class="post-actions">
						<?php if (!$this->Message->isDeleted($message) && $this->userData): ?>
								<span class="reply-to" id="reply_<?php echo $message['id'] ?>">
									<?php echo $html->replyLink($message, $dm) ?>
								</span>
							<span class="favorite">
								<?php echo $html->favoriteLink($message, $this->userData) ?>
							</span>
							<span class="delete">
							<?php echo $html->deleteMessageLink($message, $this->Message->isOwner($message)); ?>
							</span>
						<?php endif; ?>
					</div>					
					<div class="clear"></div>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<div class="inlineMessage neutral"><p><strong>Sorry, there are no messages.</strong></p></div>
		<?php endif; ?>	
			<div class="pagination"><p><?php echo Page::links($html) ?></p></div>
		</div>
