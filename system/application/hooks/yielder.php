<?php
class Yielder {
	
	function yield() {
		$ci= & get_instance();
		$current_output = $ci->output->get_output();
		$controller = $ci->uri->segment(1);
		$layout = BASEPATH ."application/views/layouts/default.php";
		if (file_exists($layout)){
			$output = $ci->load->file($layout, true);
			$output = str_replace("{yield}", $current_output,$output);
			echo $output;
		} else {
			$output = $current_output;
			echo $output;
		}
	}

}
?>