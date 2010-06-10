<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
/**
 * Nomcat
 *
 * An open source microsharing platform built on CodeIgniter
 *
 * @package   Nomcat
 * @author    NOM
 * @copyright Copyright (c) 2009, NOM llc.
 * @license   http://creativecommons.org/licenses/by-sa/3.0/
 * @link    http://getnomcat.com
 * @since   Version 1.0
 * @filesource
 */
/**
 * Hooks
 * 
 * This file lets you define "hooks" to extend CodeIgniter without hacking the core
 * files.
 * @see http://codeigniter.com/user_guide/general/hooks.html
 * @package   CodeIgniter
 * @subpackage  nomcat
 * @author    NOM
 * @link    http://getnomcat.com/user_guide/
 */

/**
 * @name $displayoverride
 * @var array $hook['display_override'][]
 */
$hook['display_override'][] = array(
  'class' => 'Yielder',
  'function' => 'yield',
  'filename' => 'yielder.php',
  'filepath' => 'hooks'
);
/**
 * @name $post_controller
 * @var array @hook['post_controller'][]
 */
$hook['post_controller'][] = array(
  'class' => 'Yielder',
  'function' => 'setlayout',
  'filename' => 'yielder.php',
  'filepath' => 'hooks',
  'params' => array('layout')
);
/**
 * @name $post_controller
 * @var array @hook['post_controller'][]
 */
$hook['post_controller'][] = array(
  'class' => 'Yielder',
  'function' => 'setsidebar',
  'filename' => 'yielder.php',
  'filepath' => 'hooks',
  'params' => array('sidebar')
);
/* End of file hooks.php */
/* Location: ./application/config/hooks.php */