<?php

//define application and system paths
$system_path = "../system";
$application_folder = "../application";

//get full system paths
if (strpos($system_path, '/') === FALSE)
{
	if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
	{
		$system_path = realpath(dirname(__FILE__)).'/'.$system_path;
	}
}
else
{
	// Swap directory separators to Unix style for consistency
	$system_path = str_replace("\\", "/", $system_path); 
}

/*
 * ---------------------------------------------------------------
 *  Resolve the system path for increased reliability
 * ---------------------------------------------------------------
 */
  if (realpath($system_path) !== FALSE)
  {
    $system_path = realpath($system_path).'/';
  }
  
  // ensure there's a trailing slash
  $system_path = rtrim($system_path, '/').'/';

  // Is the system path correct?
  if ( ! is_dir($system_path))
  {
    exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME)); 
  }



//echo $system_path;
//set path constants
define('WEBROOT', dirname(__FILE__));
define('EXT', '.'.pathinfo(__FILE__, PATHINFO_EXTENSION));
define('FCPATH', __FILE__);
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
//define('BASEPATH', $system_path.'/');
define('BASEPATH', str_replace("\\", "/", $system_path));

if (is_dir($application_folder))
{
	define('APPPATH', $application_folder.'/');
}
else
{
	if ($application_folder == '')
	{
		$application_folder = 'application';
	}

	define('APPPATH', BASEPATH . $application_folder.'/');
}

//start app
require_once BASEPATH.'core/CodeIgniter'.EXT;