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
		?><div id="<?php echo $message['id'] ?>" class="message"><?php
			$this->load->view('messages/viewpost', array('message'=>$message));
		?></div><?php
		}
	}
	?>
		</div>
	<?php
}
?>