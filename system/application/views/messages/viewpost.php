<?php
$aux = explode("|", $message);
$username = $aux[0];
$time = $aux[1];
$message = $aux[2];
echo "<div class=\"message\"><a class=\"username\" href=\"/" . $username . "\">$username</a> $message <span><a href=\"/" . $username . "/status/" . $time . "\">" . $this->time->timeAgo($time) . " ago</a></span></div>";
?>