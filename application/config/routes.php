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
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// login url rewrite
$route['login'] = 'customer/login';
$route['login/resetPasswordCustomer'] = 'customer/login/resetPasswordCustomer';
$route['login/loginCheck'] = 'customer/login/loginCheck';
$route['login/changePswd'] = 'customer/login/changePswd';
$route['login/finalChangePswd'] = 'customer/login/finalChangePswd';
$route['login/checkLoginSession'] = 'customer/login/checkLoginSession';
$route['login/logout'] = 'customer/login/logout';




/* Goal Url */
$route['goal'] = 'customer/goal';
$route['goal/addGoal'] = 'customer/goal/addGoal';
$route['goal/editGoal/(:any)'] = 'customer/goal/editGoal/$1';
$route['goal/insertGoal/(:any)'] = 'customer/goal/insertGoal/$1';
$route['goal/deleteGoal/(:any)'] = 'customer/goal/deleteGoal/$1';
$route['goal/viewchart/(:any)'] = 'customer/goal/viewchart/$1';

/*value-identifier urls */

$route['valuelist'] = 'customer/valuelist';
$route['value'] = 'customer/value';
$route['value/addValue/(:any)'] = 'customer/value/addValue/$1';
$route['value/editValue/(:any)'] = 'customer/value/editValue/$1';
$route['value/insertValue/(:any)'] = 'customer/value/insertValue/$1';
$route['value/deleteValue/(:any)'] = 'customer/value/deleteValue/$1';
$route['value/deleteAddedValue/(:any)'] = 'customer/value/deleteAddedValue/$1';


//pre-meeting url's

$route['premeeting'] = 'customer/premeeting';
$route['premeeting/addPreMeeting'] = 'customer/premeeting/addPreMeeting';
$route['premeeting/editPreMeeting/(:any)'] = 'customer/premeeting/editPreMeeting/$1';
$route['premeeting/insertPreMeeting/(:any)'] = 'customer/premeeting/insertPreMeeting/$1';
$route['premeeting/deletePreMeeting/(:any)'] = 'customer/premeeting/deletePreMeeting/$1';

//post-meeting
$route['postmeeting'] = 'customer/postmeeting';
$route['postmeeting/addPostMeeting'] = 'customer/postmeeting/addPostMeeting';
$route['postmeeting/editPostMeeting/(:any)'] = 'customer/postmeeting/editPostMeeting/$1';
$route['postmeeting/insertPostMeeting/(:any)'] = 'customer/postmeeting/insertPostMeeting/$1';
$route['postmeeting/deletePostMeeting/(:any)'] = 'customer/postmeeting/deletePostMeeting/$1';
$route['postmeeting/action_view'] = 'customer/postmeeting/action_view';
$route['postmeeting/goal_view'] = 'customer/postmeeting/goal_view';
$route['postmeeting/getSingleAction'] = 'customer/postmeeting/getSingleAction';

 
//action urls
$route['action'] = 'customer/action';
$route['action/addAction'] = 'customer/action/addAction';
$route['action/editAction/(:any)'] = 'customer/action/editAction/$1';
$route['action/addActionToNextWeek/(:any)'] = 'customer/action/addActionToNextWeek/$1';
$route['action/insertActionToNextWeek/(:any)'] = 'customer/action/insertActionToNextWeek/$1';
$route['action/insertAction/(:any)'] = 'customer/action/insertAction/$1';
$route['action/deleteAction/(:any)'] = 'customer/action/deleteAction/$1';
$route['action/completeAction/(:any)'] = 'customer/action/completeAction/$1';
$route['action/insertCompleteAction/(:any)'] = 'customer/action/insertCompleteAction/$1';