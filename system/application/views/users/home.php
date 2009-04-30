<?php echo $this->load->view('users/stats') ?>
<h2>Hello <?php echo $User['username']  ?></h2>
<h3>What are you doing?</h3>
<form action="/messages/add" method="post" accept-charset="utf-8">
	<?php echo $this->load->view('messages/postform') ?>
</form>
<?php echo $this->load->view('messages/viewlist') ?>