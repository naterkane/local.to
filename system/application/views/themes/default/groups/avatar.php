<div class="heading">
	<h2><?php echo $avatar->group($group, "48"); ?> <span><?php echo $html->groupName($group) ?></span></h2>
</div>
<div id="content">		
	<?php $this->load->view("groups/subnav/group_nav"); ?>
	<form class="grid_9 alpha" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI'] ?>">
		<fieldset>
			<legend>Upload your team's logo</legend>
		<p class="indent">
			<?php echo $avatar->group($group, "48" ); ?> 
			<?php echo $avatar->group($group, "36" ); ?> 
			<?php echo $avatar->group($group, "24" ); ?>
		</p>
		<p>
			<label for="avatar">New Image</label>
			<?php echo $form->input('avatar', array('type'=>'file')) ?>
			<span class="note">Please try to keep the file you select smaller than 200k.<br/>
			By uploading a file you certify that you have the right to distribute this picture and that it does not violate the <a href="/terms/">Terms of Service</a>.</span>
		</p>
		<p class="submit">
			<input class="button" type="submit" value="Upload"/>
		</p>
		</fieldset>
	</form>
	<div class="clear"></div>
</div>