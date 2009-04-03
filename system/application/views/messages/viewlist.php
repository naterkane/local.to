<?php
if (!empty($messages)) {
	
	foreach ($messages as $message) {
		if (!empty($message)) {
			$aux = explode("|", $message);
		    $username = $aux[0];
			if (stristr($_SERVER['REQUEST_URI'],'users/home') && $username != $User['username'])
				break;
		    $time = $aux[1];
		    $message = $aux[2];
		  	//echo $userlink = "<div class=\"message\"><a class=\"username\" href=\"/users/view/$username\">$username</a> $message <span>$time</span></div>";
			echo $userlink = "<div class=\"message\"><a class=\"username\" href=\"#\">$username</a> $message <span>" . $this->time->timeAgo($time) . " ago</span></div>";
		}
	}
}
?>