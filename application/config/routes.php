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
|	example.com/class/method/id/
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
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "index";
$route['404_override'] = '';


// CUSTOM ROUTES
$route['surveys'] = 'survey/surveys_list';
$route['surveys/(draft|open|closed|canceled)'] = 'survey/surveys_list/$1';
$route['survey/add'] = 'survey/survey_add';
$route['survey/(:num)'] = 'survey/survey_by_id/$1';
$route['survey/(:num)/edit'] = 'survey/survey_edit_by_id/$1';
$route['survey/(:num)/delete'] = 'survey/survey_delete_by_id/$1';
$route['survey/(:num)/files/(xls|xml)'] = 'survey/survey_file_download/$1/$2';
$route['survey/(:num)/(testrun|data_collection)'] = 'survey/survey_enketo/$1/$2';
// respondents
$route['survey/(:num)/respondents/(:num)'] = 'survey/survey_respondents/$1/$2';
$route['survey/(:num)/respondents'] = 'survey/survey_respondents/$1';
$route['survey/(:num)/respondents/add/(file|direct)'] = 'survey/survey_respondents_add/$1/$2';
$route['survey/(:num)/respondents/add/confirm'] = 'survey/survey_respondents_add_confirm/$1';
$route['survey/(:num)/respondents/manage/bulk'] = 'survey/survey_respondents_manage/$1/bulk';
$route['survey/(:num)/respondents/manage/(:num)/(delete)'] = 'survey/survey_respondents_manage/$1/$3/$2';

$route['survey/(:num)/xslt_transform'] = 'survey/survey_xslt_transform/$1';

$route['survey/(:num)/call_activity'] = 'survey/survey_call_activity/$1';
$route['survey/(:num)/call_activity/(completed|pending)'] = 'survey/survey_call_activity/$1/$2';
$route['survey/(:num)/data_collection/(:num)'] = 'survey/survey_enketo_single/$1/$2';

$route['survey/(:num)/change_status/(:num)'] = 'survey/survey_change_status/$1/$2';

$route['survey/(:num)/data_export/(csv_human|csv_machine)'] = 'survey/survey_export_csv/$1/$2';

// URLs for enketo. To avoid confusion will call them api
// Every method that is related to an API should start with api_[name]
$route['api/survey/request_csrf_token'] = 'survey/api_survey_request_csrf_token';
$route['api/survey/(:num)/xslt_transform'] = 'survey/api_survey_xslt_transform/$1';
$route['api/survey/(:num)/request_respondents'] = 'survey/api_survey_request_respondents/$1';
$route['api/survey/(:num)/enketo_submit'] = 'survey/api_survey_enketo_form_submit/$1';

// Other API's
$route['api/survey/(:num)/manage_agents'] = 'survey/api_survey_manage_agents/$1';

// Users
$route['login'] = 'user/user_login';
$route['logout'] = 'user/user_logout';
//$route['user'] = 'user/user_profile'; As of right now profiles are disabled.
$route['user/(:num)/edit'] = 'user/user_edit_by_id/$1';
$route['user/recover'] = 'user/user_recover_password';
$route['user/reset_password/(:any)'] = 'user/user_reset_password/$1';
$route['users'] = 'user/users_list';
$route['users/(active|blocked)'] = 'user/users_list/$1';
$route['user/add'] = 'user/user_add';
$route['user/(:num)/delete'] = 'user/user_delete_by_id/$1';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
