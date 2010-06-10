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
| There is one reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
*/

$route['default_controller'] = "welcome";
$route['scaffolding_trigger'] = "";

// tests
$route['tests/:any'] =  "tests/index";
$route['testme'] =    "tests/testme";

/**
 * Static pages
 * @todo need to make this dynamic/flexible in the future
 */
$route['about'] =   "welcome/page/about";
$route['legal'] =   "welcome/page/legal";
$route['terms'] =   "welcome/page/terms";
$route['contact'] = "welcome/page/contact";
$route['faq'] =   "welcome/page/faq";
$route['privacy'] = "welcome/page/privacy";


$route['request_invite'] =      "admin/request_invite";
$route['home'] =          "users/home";
$route['home/(:any)'] =       "users/home/$1";
$route['rss/user/(:any)'] =     "users/rss/$1";
$route['signout'] =         "users/signout";
$route['signin'] =          "users/signin";
$route['signup/(:any)'] =       "users/signup/$1";
$route['delete_account'] =      "users/delete_account";
$route['confirm/(:any)'] =      "users/confirm/$1";
$route['deny/(:any)'] =       "users/deny/$1";
$route['friend_requests'] =     "users/friend_requests";
$route['mentions'] =        "users/mentions";
$route['replies'] =         "users/mentions";
$route['favorites'] =         "users/favorites";
$route['favorites/(:any)'] =    "users/favorites/$1";
$route['settings'] =        "users/settings";
$route['settings/sms'] =      "users/notifications";
$route['settings/notifications'] =      "users/notifications";
$route['settings/avatar'] =     "users/avatar";
$route['settings/accounts'] =   "users/accounts";
$route['settings/accounts/foursquare'] = "users/foursquare_callback";
$route['avatar'] =          "users/avatar";
$route['change_password'] =     "users/change_password";
$route['recover_password'] =    "users/recover_password";
$route['reset_password/(:any)'] =   "users/reset_password/$1";
$route['reset/(:any)'] =      "users/reset/$1";
$route['followers'] =         "users/followers";
$route['following'] =         "users/following";

$route['inbox'] =           "messages/inbox";
$route['received'] =        "messages/inbox";
$route['outbox'] =          "messages/sent";
$route['sent'] =          "messages/sent";
$route['public_timeline'] =     "messages/public_timeline";

$route['group/(:any)'] =      "groups/view/$1";
$route['img/(:any)'] =        "assets/img/$1";

/**
 * @depreciated do not use
 */
$route['public_timeline_threaded'] = "messages/public_timeline_threaded";

/* End of file routes.php */
/* Location: ./application/config/routes.php */