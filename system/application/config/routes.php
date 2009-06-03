<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved 
| routes must come before any wildcard or regular expression routes.
|
*/

$route['default_controller'] = "welcome";
$route['scaffolding_trigger'] = "";

// tests
$route['tests/:any'] = "tests/index";
$route['testme'] = "tests/testme";

// static pages
$route['about'] = "welcome/page/about";
$route['home'] = "users/home";
$route['home/(:any)'] = "users/home/$1";
$route['group/(:any)'] = "groups/view/$1";
$route['signout'] = "users/signout";
$route['signin'] = "users/signin";
$route['signup/(:any)/(:any)'] = "users/signup/$1/$2";
$route['delete'] = "users/delete";
$route['confirm/(:any)'] = "users/confirm/$1";
$route['deny/(:any)'] = "users/deny/$1";
$route['friend_requests'] = "users/friend_requests";
$route['settings'] = "users/settings";
$route['settings/sms'] = "users/sms";
$route['public_timeline'] = "messages/public_timeline";
$route['public_timeline_threaded'] = "messages/public_timeline_threaded";
$route['reset/(:any)'] = "users/reset/$1";
$route['change_password'] = "users/change_password";
$route['request_invite'] = "admin/request_invite";
$route['inbox'] = "messages/inbox";
$route['sent'] = "messages/sent";
$route['rss/user/(:any)'] = "users/rss/$1";
$route['img/(:any)'] = "assets/img/$1";
$route['avatar'] = "users/avatar";
$route['recover_password'] = "users/recover_password";
$route['reset_password/(:any)'] = "users/reset_password/$1";
$route['mentions'] = "users/mentions";

/* End of file routes.php */
/* Location: ./system/application/config/routes.php */