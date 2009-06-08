<?php
if (!empty($messages)) 
{	
?>
	<div class="threads">
		<ul class="thread-list">
			<?php
			$i = 1;
	foreach ($messages as $message) 
	{
		if (!empty($message))
		{
			?>
            <li class="thread">
			<?php
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
				<div id="status-message-<?php echo $message['id'] ?>" class="post">
					<?php $this->load->view('messages/viewpost', array('message'=>$message)); ?>
				</div>
				<?php if (empty($username) && !empty($User) && $User['threading'] == 1 && !empty($message['replies'])): ?>
				<ul class="replies">
                
					<?php foreach ($message['replies'] as $reply): ?>
					<li class="reply">
							<?php $this->load->view('messages/viewpost', array('message'=>$this->Message->getOne($reply))); ?>
					</li>
					<?php endforeach ?>
				
				</ul>
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