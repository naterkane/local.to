<h2>Hello <?php echo $User['username']  ?></h2>
<h3>What are you doing?</h3>
<form action="/messages/add" method="post" accept-charset="utf-8">
	<textarea name="message" rows="8" cols="40"></textarea>
	<p><input type="submit" value="Update"></p>
</form>