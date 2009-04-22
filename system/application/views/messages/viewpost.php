<div class="message">
<?php
if ((is_array($message)) AND (!empty($message['message_html'])))
{	
	echo $this->html->link($message['username'], '/' . $message['username']);
	echo " ";
	echo $this->html->message($message['message_html']);
	echo " ";
	echo "<span>" . $this->html->link($this->time->timeAgo($message['time']) . ' ago', '/' . $message['username'] . '/status/' . $message['id'], array('id'=>'messagelink' . $message['id'])) . "</span>";
}
?>
</div>