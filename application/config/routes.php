<?php
defined('BASEPATH') or exit('No direct script access allowed');

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

$crm = 'crm/';
//Daddy CRUD MASTER Routes
//Crud Master
$crud_master = $crm . "Crudmasterstatic/";
$route['crudmaster'] = $crud_master . 'index';
$route['crudmaster/index'] = $crud_master . 'index';
$route['crudmaster/list'] = $crud_master . 'list';
$route['crudmaster/post_actions/(:any)'] = $crud_master . 'post_actions/$1';

//Rudra_testw_crtl ROUTES
$crud_master = $crm . "Rudra_testw_crtl/";
$route['rudra_testw'] = $crud_master . 'index';
$route['rudra_testw/index'] = $crud_master . 'index';
$route['rudra_testw/list'] = $crud_master . 'list';
$route['rudra_testw/post_actions/(:any)'] = $crud_master . 'post_actions/$1';

$Rudra_faq_crtl = $crm . "Rudra_faq_crtl/";
$route['rudra_faq'] = $Rudra_faq_crtl . 'index';
$route['rudra_faq/index'] = $Rudra_faq_crtl . 'index';
$route['rudra_faq/list'] = $Rudra_faq_crtl . 'list';
$route['rudra_faq/post_actions/(:any)'] = $Rudra_faq_crtl . 'post_actions/$1';

$Rudra_user_crtl = $crm . "Rudra_user_crtl/";
$route['rudra_user'] = $Rudra_user_crtl . 'index';
$route['rudra_user/index'] = $Rudra_user_crtl . 'index';
$route['rudra_user/list'] = $Rudra_user_crtl . 'list';
$route['rudra_user/post_actions/(:any)'] = $Rudra_user_crtl . 'post_actions/$1';
$route['rudra_user/view/(:num)'] = $Rudra_user_crtl . 'viewDetail/$1';
$route['washers'] = $Rudra_user_crtl . 'washers';
$route['washer/view/(:num)'] = $Rudra_user_crtl . 'viewDetailwasher/$1';
$route['rudra_user/add'] = $Rudra_user_crtl . 'add_user';


$Rudra_washer_bank_info_crtl = $crm . "Rudra_washer_bank_info_crtl/";
$route['rudra_washer_bank_info'] = $Rudra_washer_bank_info_crtl . 'index';
$route['rudra_washer_bank_info/index'] = $Rudra_washer_bank_info_crtl . 'index';
$route['rudra_washer_bank_info/list'] = $Rudra_washer_bank_info_crtl . 'list';
$route['rudra_washer_bank_info/post_actions/(:any)'] = $Rudra_washer_bank_info_crtl . 'post_actions/$1';

$Rudra_wash_types_crtl = $crm . "Rudra_wash_types_crtl/";
$route['rudra_wash_types'] = $Rudra_wash_types_crtl . 'index';
$route['rudra_wash_types/index'] = $Rudra_wash_types_crtl . 'index';
$route['rudra_wash_types/list'] = $Rudra_wash_types_crtl . 'list';
$route['rudra_wash_types/post_actions/(:any)'] = $Rudra_wash_types_crtl . 'post_actions/$1';

$Rudra_wash_extra_types_crtl = $crm . "Rudra_wash_extra_types_crtl/";
$route['rudra_wash_extra_types'] = $Rudra_wash_extra_types_crtl . 'index';
$route['rudra_wash_extra_types/index'] = $Rudra_wash_extra_types_crtl . 'index';
$route['rudra_wash_extra_types/list'] = $Rudra_wash_extra_types_crtl . 'list';
$route['rudra_wash_extra_types/post_actions/(:any)'] = $Rudra_wash_extra_types_crtl . 'post_actions/$1';

$Rudra_wash_comments_crtl = $crm . "Rudra_wash_comments_crtl/";
$route['rudra_wash_comments'] = $Rudra_wash_comments_crtl . 'index';
$route['rudra_wash_comments/index'] = $Rudra_wash_comments_crtl . 'index';
$route['rudra_wash_comments/list'] = $Rudra_wash_comments_crtl . 'list';
$route['rudra_wash_comments/post_actions/(:any)'] = $Rudra_wash_comments_crtl . 'post_actions/$1';

$Rudra_user_registered_car_numbers_crtl = $crm . "Rudra_user_registered_car_numbers_crtl/";
$route['rudra_user_registered_car_numbers'] = $Rudra_user_registered_car_numbers_crtl . 'index';
$route['rudra_user_registered_car_numbers/index'] = $Rudra_user_registered_car_numbers_crtl . 'index';
$route['rudra_user_registered_car_numbers/list'] = $Rudra_user_registered_car_numbers_crtl . 'list';
$route['rudra_user_registered_car_numbers/post_actions/(:any)'] = $Rudra_user_registered_car_numbers_crtl . 'post_actions/$1';
$route['rudra_user_registered_car_numbers/view/(:num)'] = $Rudra_user_registered_car_numbers_crtl . 'viewDetail/$1';

$Rudra_booking_crtl = $crm . "Rudra_booking_crtl/";
$route['rudra_booking'] = $Rudra_booking_crtl . 'index';
$route['rudra_booking/index'] = $Rudra_booking_crtl . 'index';
$route['rudra_booking/list'] = $Rudra_booking_crtl . 'list';
$route['rudra_booking/post_actions/(:any)'] = $Rudra_booking_crtl . 'post_actions/$1';
$route['rudra_booking/view/(:num)'] = $Rudra_booking_crtl . 'viewDetail/$1';
$route['remove_new'] = $Rudra_booking_crtl . 'remove_new';
$route['get_new_bookings'] = $Rudra_booking_crtl . 'get_new_bookings';
$route['rudra_booking/pending'] = $Rudra_booking_crtl . 'pending';
$route['rudra_booking/accepted'] = $Rudra_booking_crtl . 'assigned';
$route['rudra_booking/completed'] = $Rudra_booking_crtl . 'completed';

$Rudra_chat_crtl = $crm . "Rudra_chat_crtl/";
$route['rudra_chat'] = $Rudra_chat_crtl . 'index';
$route['rudra_chat/index'] = $Rudra_chat_crtl . 'index';
$route['rudra_chat/list'] = $Rudra_chat_crtl . 'list';
$route['rudra_chat/user'] = $Rudra_chat_crtl . 'chat';
$route['rudra_chat/get_chats'] = $Rudra_chat_crtl . 'getChatList';
$route['rudra_chat/post_actions/(:any)'] = $Rudra_chat_crtl . 'post_actions/$1';
$route['rudra_chat/messages'] = $Rudra_chat_crtl . 'messages';
$route['rudra_chat/new_messages'] = $Rudra_chat_crtl . 'new_messages';
$route['rudra_chat/all_messages'] = $Rudra_chat_crtl . 'messages';
$route['get_new_chats'] = $Rudra_chat_crtl . 'get_new_chats';

$Rudra_chat_uploads_crtl = $crm . "Rudra_chat_uploads_crtl/";
$route['rudra_chat_uploads'] = $Rudra_chat_uploads_crtl . 'index';
$route['rudra_chat_uploads/index'] = $Rudra_chat_uploads_crtl . 'index';
$route['rudra_chat_uploads/list'] = $Rudra_chat_uploads_crtl . 'list';
$route['rudra_chat_uploads/post_actions/(:any)'] = $Rudra_chat_uploads_crtl . 'post_actions/$1';

$Rudra_help_crtl = $crm . "Rudra_help_crtl/";
$route['rudra_help'] = $Rudra_help_crtl . 'index';
$route['rudra_help/index'] = $Rudra_help_crtl . 'index';
$route['rudra_help/list'] = $Rudra_help_crtl . 'list';
$route['rudra_help/post_actions/(:any)'] = $Rudra_help_crtl . 'post_actions/$1';

$Rudra_notifications_crtl = $crm . "Rudra_notifications_crtl/";
$route['rudra_notifications'] = $Rudra_notifications_crtl . 'index';
$route['rudra_notifications/index'] = $Rudra_notifications_crtl . 'index';
$route['rudra_notifications/list'] = $Rudra_notifications_crtl . 'list';
$route['rudra_notifications/post_actions/(:any)'] = $Rudra_notifications_crtl . 'post_actions/$1';

//Rudra_slots_crtl ROUTES
$Rudra_slots_crtl = $crm . "Rudra_slots_crtl/";
$route['rudra_slots'] = $Rudra_slots_crtl . 'index';
$route['rudra_slots/index'] = $Rudra_slots_crtl . 'index';
$route['rudra_slots/list'] = $Rudra_slots_crtl . 'list';
$route['rudra_slots/post_actions/(:any)'] = $Rudra_slots_crtl . 'post_actions/$1';

//Rudra_threshold_crtl ROUTES
$Rudra_threshold_crtl = $crm . "Rudra_threshold_crtl/";
$route['rudra_threshold'] = $Rudra_threshold_crtl . 'index';
$route['rudra_threshold/index'] = $Rudra_threshold_crtl . 'index';
$route['rudra_threshold/list'] = $Rudra_threshold_crtl . 'list';
$route['rudra_threshold/post_actions/(:any)'] = $Rudra_threshold_crtl . 'post_actions/$1';

//Rudra_payments_crtl ROUTES
$Rudra_payments_crtl = $crm . "Rudra_payments_crtl/";
$route['rudra_payments'] = $Rudra_payments_crtl . 'index';
$route['rudra_payments/index'] = $Rudra_payments_crtl . 'index';
$route['rudra_payments/list'] = $Rudra_payments_crtl . 'list';
$route['rudra_payments/post_actions/(:any)'] = $Rudra_payments_crtl . 'post_actions/$1';

//Rudra_coupons_crtl ROUTES
$Rudra_coupons_crtl = $crm . "Rudra_coupons_crtl/";
$route['rudra_coupons'] = $Rudra_coupons_crtl . 'index';
$route['rudra_coupons/index'] = $Rudra_coupons_crtl . 'index';
$route['rudra_coupons/list'] = $Rudra_coupons_crtl . 'list';
$route['rudra_coupons/post_actions/(:any)'] = $Rudra_coupons_crtl . 'post_actions/$1';

//Rudra_zipcodes_crtl ROUTES
$Rudra_zipcodes_crtl = $crm . "Rudra_zipcodes_crtl/";
$route['rudra_zipcodes'] = $Rudra_zipcodes_crtl . 'index';
$route['rudra_zipcodes/index'] = $Rudra_zipcodes_crtl . 'index';
$route['rudra_zipcodes/list'] = $Rudra_zipcodes_crtl . 'list';
$route['rudra_zipcodes/post_actions/(:any)'] = $Rudra_zipcodes_crtl . 'post_actions/$1';

//Rudra_map_crtl ROUTES
$Rudra_map_crtl = $crm . "Rudra_map_crtl/";
$route['rudra_map'] = $Rudra_map_crtl . 'index';
$route['rudra_map/index'] = $Rudra_map_crtl . 'index';
$route['rudra_map/list'] = $Rudra_map_crtl . 'list';
$route['rudra_map/post_actions/(:any)'] = $Rudra_map_crtl.'post_actions/$1';

//Rudra_times_crtl ROUTES
$Rudra_times_crtl = $crm . "Rudra_times_crtl/";
$route['rudra_times'] = $Rudra_times_crtl . 'index';
$route['rudra_times/index'] = $Rudra_times_crtl . 'index';
$route['rudra_times/list'] = $Rudra_times_crtl . 'list';
$route['rudra_times/post_actions/(:any)'] = $Rudra_times_crtl.'post_actions/$1';

//Daddy Admin Codes
$route['crm/admin'] = 'crm/admin';
$route['admin-login'] = 'crm/admin/login';
$route['do-admin-login'] = 'crm/Admin/check_login_admin';
$route['admin'] = 'crm/Admin/index';
$route['dashboard-data'] = 'crm/Admin/get_dashboard_data';
$route['load-ajax-data'] = 'crm/Dashboard_ajax/get_data';
$route['logout'] = 'crm/Admin/logout';


/* API */
//base_url for API : {{http://localhost/fxd/api/v1/}}
$api_ver = 'api/v1/';
//rudra_user API Routes
$user = 'auto_scripts/Rudra_user_apis/';
$route[$api_ver . 'user/(:any)'] = $user . 'rudra_rudra_user/$1';

$user_registered_car_numbers = 'auto_scripts/Rudra_user_registered_car_numbers_apis/';
$route[$api_ver . 'register-car/(:any)'] = $user_registered_car_numbers . 'rudra_rudra_user_registered_car_numbers/$1';

$Rudra_wash_types_apis = 'auto_scripts/Rudra_wash_types_apis/';
$route[$api_ver . 'wash-types/(:any)'] = $Rudra_wash_types_apis . 'rudra_rudra_wash_types/$1';

$Rudra_wash_extra_types_apis = 'auto_scripts/Rudra_wash_extra_types_apis/';
$route[$api_ver . 'extra-wash-types/(:any)'] = $Rudra_wash_extra_types_apis . 'rudra_rudra_wash_extra_types/$1';

$rudra_rudra_booking = 'auto_scripts/Rudra_booking_apis/';
$route[$api_ver . 'booking/(:any)'] = $rudra_rudra_booking . 'rudra_rudra_booking/$1';

$rudra_rudra_wash_comments = 'auto_scripts/Rudra_wash_comments_apis/';
$route[$api_ver . 'wash-comment/(:any)'] = $rudra_rudra_wash_comments . 'rudra_rudra_wash_comments/$1';

$rudra_rudra_faq = 'auto_scripts/Rudra_faq_apis/';
$route[$api_ver . 'faq/(:any)'] = $rudra_rudra_faq . 'rudra_rudra_faq/$1';

$rudra_rudra_chat = 'auto_scripts/Rudra_chat_apis/';
$route[$api_ver . 'chat/(:any)'] = $rudra_rudra_chat . 'rudra_rudra_chat/$1';

$rudra_rudra_washer_bank_info = 'auto_scripts/Rudra_washer_bank_info_apis/';
$route[$api_ver . 'washer_bank/(:any)'] = $rudra_rudra_washer_bank_info . 'rudra_rudra_washer_bank_info/$1';

$rudra_rudra_payments = 'auto_scripts/Rudra_payments_apis/';
$route[$api_ver . 'payments/(:any)'] = $rudra_rudra_payments . 'rudra_rudra_payments/$1';

$rudra_rudra_notifications = 'auto_scripts/Rudra_notifications_apis/';
$route[$api_ver . 'notifications/(:any)'] = $rudra_rudra_notifications . 'rudra_rudra_notifications/$1';

$rudra_rudra_zipcodes = 'auto_scripts/Rudra_zipcodes_apis/';
$route[$api_ver . 'zipcodes/(:any)'] = $rudra_rudra_zipcodes . 'rudra_rudra_zipcodes/$1';

$Rudra_map_apis = 'auto_scripts/Rudra_map_apis/';    
$route[$api_ver.'map/(:any)'] = $Rudra_map_apis.'rudra_rudra_map/$1';
/* API */

$route['test-map'] = "Welcome/map";
$route['terms-conditions'] = "Welcome/termsConditions";
$route['privacy-policy'] = "Welcome/privacyPolicy";

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['payment-accept'] = $Rudra_payments_crtl . 'payment_accept';
$route['payment-cancel'] = $Rudra_payments_crtl . 'payment_cancel';
$route['payment-callback'] = $Rudra_payments_crtl . 'payment_callback';

$route['payment/nets'] = $Rudra_payments_crtl . 'pay_nets';
$route['payment/create-payment'] = $Rudra_payments_crtl . 'create_payment';
$route['payment/checkout'] = $Rudra_payments_crtl . 'checkout';
$route['payment/completed'] = $Rudra_payments_crtl . 'payment_completed';
$route['payment_success'] = $Rudra_payments_crtl . 'payment_success';
$route['payment/404'] = $Rudra_payments_crtl . 'payment_failed';


$route['cron/sendnotification'] = 'cron/Cron/sendnotification';


$route['payments'] = $Rudra_payments_crtl . 'payment_accept';
$route['payments/cancel'] = $Rudra_payments_crtl . 'payment_cancel';
$route['payments/refunds'] = $Rudra_payments_crtl . 'payment_callback';


$route['callback'] = $Rudra_payments_crtl . 'mobilepay_callback';

