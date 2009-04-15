<div class="message">
<?php
if (is_array($message)) 
{	
	echo $this->html->link($message['username'], '/' . $message['username']);
	echo " ";
	echo $this->html->message($message['message']);
	echo " ";
	echo "<span>" . $this->html->link($this->time->timeAgo($message['time']) . ' ago', '/' . $message['username'] . '/status/' . $message['time']) . "</span>";
}
?>
</div>