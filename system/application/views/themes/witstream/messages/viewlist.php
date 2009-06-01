<?php
if (!empty($messages)) 
{	
?>
	<div class="block" id="messages">
			<?php
			$i = 1;
	foreach ($messages as $message) 
	{
		if (!empty($message))
		{
			$show = true;
			if (!empty($username))
			{
				$show = true;
			}
			elseif(!empty($User) && $User['threading'] == 1 && !empty($message) && $message['reply_to'] != null)
			{
				$show = false;
			}
			if ($show)	
			{			
			?>
				<div id="status-message-<?php echo $message['id'] ?>" class="message">
					<?php $this->load->view('messages/viewpost', array('message'=>$message)); ?>
				</div>
				<?php if (empty($username) && !empty($User) && $User['threading'] == 1 && !empty($message['replies'])): ?>
					<?php foreach ($message['replies'] as $reply): ?>
						<div id="status-message-<?php echo $reply ?>" class="message reply" style="margin-left:90px;">
							<?php $this->load->view('messages/viewpost', array('message'=>$this->Message->getOne($reply))); ?>
						</div>	
					<?php endforeach ?>
					hello
				<?php endif ?>				
			<?php
			}
		}
		else
		{
			?><p>Looks like there aren't any messages yet... </p><?php	
		}
	}
	?>
		<?php echo Page::links($html) ?>
		</div>
	<?php
}
?>