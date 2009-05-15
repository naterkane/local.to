<?php  
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['display_override'][] = array(
	'class' => 'Yielder',
	'function' => 'yield',
	'filename' => 'yielder.php',
	'filepath' => 'hooks'
);
$hook['post_controller'][] = array(
	'class' => 'Yielder',
	'function' => 'setlayout',
	'filename' => 'yielder.php',
	'filepath' => 'hooks',
	'params' => array('layout')
);
$hook['post_controller'][] = array(
	'class' => 'Yielder',
	'function' => 'setsidebar',
	'filename' => 'yielder.php',
	'filepath' => 'hooks',
	'params' => array('sidebar')
);
/* End of file hooks.php */
/* Location: ./system/application/config/hooks.php */
?>