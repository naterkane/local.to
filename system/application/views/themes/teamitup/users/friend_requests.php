<h2>Friend Requests</h2>

<?php if (empty($requests)): ?>
	<p>You have no friend requests at this time.</p>
<?php else: ?>
	<?php foreach ($requests as $request): ?>
		<p>
			<a href="/<?php echo $request['username'] ?>"><?php echo $request['username'] ?></a> 
			(<a href="/confirm/<?php echo $request['username'] ?>" id="confirm<?php echo $request['username'] ?>">Accept</a>) 
			(<a href="/deny/<?php echo $request['username'] ?>" id="deny<?php echo $request['username'] ?>">Deny</a>)
		</p>
	<?php endforeach ?>
<?php endif ?>