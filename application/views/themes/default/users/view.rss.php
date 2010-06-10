	<channel>
		<title><?php echo $this->config->item('service_name') ?> / <?php echo $page_title ?></title>
		<link><?php echo $this->config->item('base_url') ?></link>
		<atom:link type="application/rss+xml" rel="self" href="<?php echo $this->config->item('base_url') . $user['username'] ?>"/>
		<description><?php echo $this->config->item('service_name') ?> updates from <?php echo $user['username'] ?>.</description>
		<language>en-us</language>
		<ttl>30</ttl>
	<?php foreach ($messages as $message): ?>
		<item>
			<title><?php echo $message['message'] ?></title>
			<description><?php echo $message['message'] ?></description>
			<pubDate><?php echo date(DATE_RSS, $message['time']) ?></pubDate>
			<guid><?php echo $this->config->item('base_url') . $message['username'] . '/status/' . $message['id'] ?></guid>
			<link><?php echo $this->config->item('base_url') . $message['username'] . '/status/' . $message['id'] ?></link>
		</item>
	<?php endforeach ?>
	</channel>