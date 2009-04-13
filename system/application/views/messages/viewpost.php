<?php
	$aux = explode("|", $message);
	// hack workaround if a user includes a pipe | character in their post
	$username = array_shift($aux);
	$time = array_shift($aux);
	$message = array_shift($aux);
	while(count($aux)>0)
	{
		$message .= "|".array_shift($aux);
	}
?>
<div class="message">
<?php
echo $this->html->link($username, '/' . $username);
echo " ";
echo $this->html->message($message);
echo " ";
echo "<span>" . $this->html->link($this->time->timeAgo($time) . ' ago', '/' . $username . '/status/' . $time) . "</span>";
?>
</div>