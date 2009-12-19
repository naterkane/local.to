<?php if ($friend_select): ?>
	<form action="/messages/add?redirect=/inbox" class="form" method="post" accept-charset="utf-8">
		<fieldset>
			<p>
				<label class="dm">
					Send <?php echo $form->optgroup('to', $friend_select, array('first_label'=>'- Select a recipient -')) ?> a private message
				</label>
				<span class="character-count" id="character-count">&nbsp;</span>
			</p>
		</fieldset>
		<fieldset>
		<?php echo $this->load->view('messages/postform') ?>
		</fieldset>
	</form>
<?php else: ?>
	<p class="notice">You have no followers. You can only send private messages if you have followers.</p>
<?php endif ?>