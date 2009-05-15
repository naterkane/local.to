<p>Thread replies: <?php
if ($User['threading'] == 1):
	?><strong>On</strong> <a href="/users/threading/disable/<?php echo base64_encode($_SERVER['REQUEST_URI']) ?>" class="toggler">Off</a><?php		
else:
	?><a href="/users/threading/enable/<?php echo base64_encode($_SERVER['REQUEST_URI']) ?>" class="toggler">On</a> <strong>Off</strong><?php
endif;
?>	
</p>