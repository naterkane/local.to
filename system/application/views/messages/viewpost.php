<?php
if ((is_array($message)) AND (!empty($message['message_html'])))
{
?>
<hr/>
<div class="message">
<span class="author"><?php echo $html->link($message['username'], '/' . $message['username']); ?> </span>
<span class="message"><?php echo $message['message_html'] ?></span>
<span class="meta"><?php echo $html->link($time_format->timeAgo($message['time']) . ' ago', '/' . $message['username'] . '/status/' . $message['id'], array('id'=>'messagelink' . $message['id']));?></span>
</div>
<?php } ?>