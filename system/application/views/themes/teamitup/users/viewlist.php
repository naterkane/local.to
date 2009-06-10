<?php if (!empty($users)): ?>
	<?php foreach ($users as $user): ?>
		<?php if (!empty($user)): ?>
			<?php echo $user['username'] ?>
		<?php endif ?>
	<?php endforeach ?>
	<?php echo Page::links($html) ?>	
<?php endif ?>
