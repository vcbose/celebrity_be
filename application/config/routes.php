<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically rouet
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller']= 'users/login';

$route['admin'] 			= 'admin/admin/index';
$route['cb-admin'] 			= 'users/login';
$route['signin'] 			= 'users/login';
$route['logout'] 			= 'users/logout';
$route['auth-user'] 		= 'users/auth_user';
$route['register'] 			= 'users/registration';
$route['profiles'] 			= 'users/profiles';
$route['profile-detail/([a-z]+)/(\d+)'] 		= 'users/profile_detail/$1/id_$2';
$route['getplandata']		= 'users/getplandata';
// $route['notifications']	= 'users/notifications';
$route['checkUserName'] = 'users/checkUserName';
$route['dashboard'] 		= 'users/talents/dashboard/';
$route['plans/(:num)'] 		= 'plans/index';
$route['subscriptions/(:num)'] = 'plans/subscriptions/user_id/$1';
$route['notifications']		= 'notifications/manage_notification';
$route['get-notifications']	= 'notifications/get_notifications';
$route['get-interview']		= 'notifications/get_interview';
$route['user-notifications']= 'notifications/index/';
$route['chat/(:num)'] 		= 'notifications/chat/user_id/$1';
$route['submit-chat'] 		= 'notifications/submit_chat';
$route['get-chat'] 			= 'notifications/get_chat';

$route['404_override'] 	= '';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| REST API Routes
| -------------------------------------------------------------------------
*/
$route['api/v1/auth']         			= 'api/v1/Authenticate_api/auth';
$route['api/v1/settings']         		= 'api/v1/Setting_api/setting';
$route['api/v1/settings/(:num)']        = 'api/v1/Setting_api/setting/setting_id/$1';
$route['api/v1/register']         		= 'api/v1/User_api/register';
$route['api/v1/users']         			= 'api/v1/User_api/user';
$route['api/v1/usercount']         		= 'api/v1/User_api/usercount';
$route['api/v1/users/(:num)']        	= 'api/v1/User_api/user/cbu.user_id/$1';
$route['api/v1/highlightusers']       	= 'api/v1/User_api/hightlight_user';
$route['api/v1/highlightusers/(:num)'] 	= 'api/v1/User_api/hightlight_user/cbs.user_id/$1';
$route['api/v1/usermedias']        		= 'api/v1/Usermedia_api/usermedia';
$route['api/v1/usermedias/(:num)']  	= 'api/v1/Usermedia_api/usermedia/user_id/$1';
$route['api/v1/plans']         			= 'api/v1/Plans_api/plans';
$route['api/v1/plans/(:num)']        	= 'api/v1/Plans_api/plans/plan_id/$1';
// $route['api/v1/features']         		= 'api/v1/Subscriptions_api/features';
$route['api/v1/features/(:num)']   		= 'api/v1/Subscriptions_api/features/user_id/$1';
// $route['api/v1/subscriptions']         	= 'api/v1/Subscriptions_api/subscriptions';
$route['api/v1/subscriptions/(:num)']   = 'api/v1/Subscriptions_api/subscriptions/user_id/$1';
$route['api/v1/interests']         		= 'api/v1/Interest_api/interests';
$route['api/v1/interests/(:num)']   	= 'api/v1/Interest_api/interests/user_id/$1';
$route['api/v1/interestcount/(:num)'] 	= 'api/v1/Interest_api/interest_count/user_id/$1';
$route['api/v1/userchats']         		= 'api/v1/Userchat_api/userchats';
$route['api/v1/userchats/(:num)']   	= 'api/v1/Userchat_api/userchats/chat_id/$1';
$route['api/v1/chatusers/(:num)']       = 'api/v1/Userchat_api/chatusers/chat_to/$1';
$route['api/v1/chatusercount/(:num)']   = 'api/v1/Userchat_api/chatuser_count/chat_to/$1';
$route['api/v1/interviews']         	= 'api/v1/Interview_api/interviews';
$route['api/v1/interviews/(:num)']   	= 'api/v1/Interview_api/interviews/user_id/$1';
$route['api/v1/interviewcount/(:num)'] 	= 'api/v1/Interview_api/interview_count/user_id/$1';
$route['api/v1/visits']         		= 'api/v1/Visit_api/visits';
$route['api/v1/visits/(:num)']   		= 'api/v1/Visit_api/visits/user_id/$1';
$route['api/v1/visitcount/(:num)']   	= 'api/v1/Visit_api/visit_count/user_id/$1';

