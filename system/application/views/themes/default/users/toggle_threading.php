<?php 
if (!empty($this->userData)): ?>
	<div class="box">
		<p>Thread replies: <?php
		if ($this->userData['threading'] == 1):
			?><strong>On</strong> <a href="/users/threading/disable<?php echo $html->sendMeHere() ?>" class="toggler">Off</a><?php		
		else:
			?><a href="/users/threading/enable<?php echo $html->sendMeHere() ?>" class="toggler">On</a> <strong>Off</strong><?php
		endif;
		?></p>
	</div>
<?php endif;