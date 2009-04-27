<?php
	/*
	$aux = explode("|", $message);
	// hack workaround if a user includes a pipe | character in their post
	$username = array_shift($aux);
	$time = array_shift($aux);
	$message = array_shift($aux);
	while(count($aux)>0)
	{
		$message .= "|".array_shift($aux);
	}
	*/
if ((is_array($message)) AND (!empty($message['message_html'])))
{
?>
<hr/>
<div class="message">
<span class="author"><?php echo $this->html->link($message['username'], '/' . $message['username']); ?> </span>
<span class="message"><?php echo $message['message_html'] ?></span>
<span class="meta"><?php echo $this->html->link($this->time->timeAgo($message['time']) . ' ago', '/' . $message['username'] . '/status/' . $message['id'], array('id'=>'messagelink' . $message['id']));?></span>
</div>
<?php } ?>