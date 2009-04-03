<?php
if (!empty($messages)) 
{
	
	foreach ($messages as $message) 
	{
		if (!empty($message)) 
		{
			$aux = explode("|", $message);
		    $postid = $aux[0];
			$username = $aux[1];
			
			if (!(stristr($_SERVER['REQUEST_URI'],'users/home') && $username != $User['username']))
			{
			    $time = $aux[2];
			    $message = $aux[3];
			  	//echo $userlink = "<div class=\"message\"><a class=\"username\" href=\"/users/view/$username\">$username</a> $message <span>$time</span></div>";
				echo $userlink = "<div class=\"message\"><a class=\"username\" href=\"/$username\">$username</a> $message <span><a href=\"/messages/view/$postid\">" . $this->time->timeAgo($time) . " ago</a></span></div>";
			}
		}
	}
}
?>