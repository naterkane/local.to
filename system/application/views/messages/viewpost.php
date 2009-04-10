<?php
	$aux = explode("|", $message);
	$username = $aux[0];
	$time = $aux[1];
	$message = $aux[2];
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