<?php
if (!empty($messages)) 
{	
?>
		<div class="block" id="messages">
			<?php
	foreach ($messages as $message) 
	{
		if (!empty($message))
		{
			$show = true;
			// if we're viewing a user's profile don't thread the messages
			if (!empty($username))
			{
				$show = true;
			}
			elseif(	!empty($User)  && $User['threading'] == 1 && !empty($message) && $message['reply_to'] != null)
			{
				$show = false;//echo "replyto ".$message['reply_to']."<br/>";
				//echo "replies ".var_dump($message['replies'])."<br/>";	
			}
			//else
			//{
			if ($show)	
			{			
				?><div id="status-message-<?php echo $message['id'] ?>" class="message">
					<?php $this->load->view('messages/viewpost', array('message'=>$message)); ?>
				</div><?php
				if (empty($username) && !empty($User) && $User['threading'] == 1 && !empty($message['replies']))
				{
					foreach ($message['replies'] as $reply)
					{
						?><div id="status-message-<?php echo $reply ?>" class="message reply" style="margin-left:90px;">
							<?php $this->load->view('messages/viewpost', array('message'=>$this->Message->getOne($reply))); ?>
						</div><?php	
					}
				}
			}
			//}
		}
		else
		{
			?><p>Looks like there aren't any messages yet... </p><?php	
		}
	}
	?>
		</div>
	<?php
}
?>