<div class="heading">
	<h2>Mentions</h2>
</div>
<div id="content">
	<div class="inlineMessage"><p>Anytime your username (@<a href="/<?php echo $this->userData['username']; ?>"><?php echo $this->userData['username']; ?></a>) is tagged in a public update or reply.</p></div>
	<?php $this->load->view('messages/viewlist',array('messages'=>$messages)); ?>
</div>