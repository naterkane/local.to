<div>
    
	<?php if (empty($this->userData) && isset($username)): ?>
		<div class="top">
			
			<h3>Hey there! <strong><?php echo $username ?></strong> is using <?php echo $this->config->item('service_name')?></h3>
			<p><?php echo $this->config->item('service_name')?> is a free service that lets you keep in touch with people through the exchange of quick, frequent answers to one simple question: What are you doing? Join today to start receiving <?php echo $username ?>'s updates.</p>
			
		</div>
		<?php else: ?>
		<div class="top short">
        <h3>
            <?php
            echo $profile['username'];
            ?>
        </h3>
    </div>
		<?php endif; ?>
    <div class="box">
        <pre><?php var_dump($profile); ?></pre>
    </div>
</div>
