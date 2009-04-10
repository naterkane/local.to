<?php if (!empty($User)) {
	echo $this->load->view('messages/postform');
}
?>
<h3>Public Timeline</h3>
<?php echo $this->load->view('messages/viewlist') ?>