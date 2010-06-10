<div class="heading">
	<h2><?php echo $avatar->group($group, "48"); ?> <span><?php echo $html->groupName($group) ?></span></h2>
</div>
<div id="content">	
	<h3>File Sharing <a href="/groups/uploadfiles/<?php echo $group['name']?>" class="button add">Upload files</a></h3>
		
	<?php 
	if (!empty($files)):
		?><div id="files">
		<p class="inlineMessage">You can also view these files on drop<span style="color:#be202e;">.io</span> at <a href="http://drop.io/<?php echo $drop['values']['name']?>">http://drop.io/<?php echo $drop['values']['name']?></a> password: </p>
	
		<?php 
		foreach($files as $file){
			?><div class="file">
				<a href="<?php echo $file['values']['converted']?>" class="thumbnail"><?php 
			if($file['values']['type'] == "image"){
					?><img src="<?php echo $file['values']['thumbnail']?>" alt="<?php echo $file['values']['title']?>"/><?php 
			}
			?></a> 
			<p class="name">
				<a href="<?php echo $file['values']['converted']?>"><?php echo $file['values']['title']?></a>
			</p>
			<p class="meta">Uploaded on <?php echo $file['values']['created_at']?> by <?php echo $file['values']['description']?></p>
			</div><?php 
		}
		?></div><?php 
	endif;
		
	?>
	<a href="/groups/uploadfiles/<?php echo $group['name']?>" class="button add">Upload files</a>
	
</div>