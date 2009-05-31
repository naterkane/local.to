<?php 
if (!empty($this->userData)): ?>
	<div class="box">
		<p>Thread replies: <?php
		if ($this->userData['threading'] == 1):
			?><strong>On</strong> <a href="/users/threading/disable/<?php echo $this->util->base64_url_encode($_SERVER['REQUEST_URI']) ?>" class="toggler">Off</a><?php		
		else:
			?><a href="/users/threading/enable/<?php echo $this->util->base64_url_encode($_SERVER['REQUEST_URI']) ?>" class="toggler">On</a> <strong>Off</strong><?php
		endif;
		?></p>
	</div>
<?php endif;