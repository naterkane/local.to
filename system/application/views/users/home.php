<h2>What are you doing?</h2>
<form action="/messages/add" method="post" accept-charset="utf-8">
	<textarea name="message" rows="8" cols="40"></textarea>
	<p><input type="submit" value="Update"></p>
</form>
<h3>Hello <?php echo $User['username']  ?></h3>
<?php echo $this->load->view('messages/viewlist') ?>