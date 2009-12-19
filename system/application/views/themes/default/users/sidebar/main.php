<?php if (!empty($profile)): ?>
	<div class="clear"></div>
	<div id="sb">
		<?php
		if ($this->isSignedIn && !$this->isProfile) 
		{
			$this->load->view('users/sidebar/profile', array('user'=>$profile));
		}
		else 
		{
			$this->load->view('users/sidebar/stats', array('user'=>$profile));
		}
		$this->load->view('users/sidebar/menu', array('user'=>$profile));
		$this->load->view('users/sidebar/following', array('user'=>$profile));
		?>
	</div>
<?php endif; ?>