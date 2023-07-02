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
$route['default_controller'] = 'user/index';
$route['404_override'] = '';

/*admin*/
$route['admin'] = 'user/index';
$route['admin/signup'] = 'user/signup';
$route['admin/create_member'] = 'user/create_member';
$route['admin/login'] = 'user/index';
$route['admin/logout'] = 'user/logout';
$route['admin/login/validate_credentials'] = 'user/validate_credentials';

$route['admin/users'] = 'admin_users/index';
$route['admin/users/add'] = 'admin_users/add';
$route['admin/users/update'] = 'admin_users/update';
$route['admin/users/update/(:any)'] = 'admin_users/update/$1';
$route['admin/users/delete/(:any)'] = 'admin_users/delete/$1';
$route['admin/users/(:any)'] = 'admin_users/index/$1'; //$1 = page number

$route['admin/prayers/add/(:any)'] = 'admin_prayers/add/$1';
$route['admin/prayers/update/(:any)'] = 'admin_prayers/update/$1';
$route['admin/prayers/delete/(:any)'] = 'admin_prayers/delete/$1';
$route['admin/prayers/(:any)/(:any)'] = 'admin_prayers/index/$1/$2'; //$2 = page number
$route['admin/prayers/(:any)'] = 'admin_prayers/index/$1';

$route['admin/categories'] = 'admin_categories/index';
$route['admin/categories/add'] = 'admin_categories/add';
$route['admin/categories/update'] = 'admin_categories/update';
$route['admin/categories/update/(:any)'] = 'admin_categories/update/$1';
$route['admin/categories/delete/(:any)'] = 'admin_categories/delete/$1';
$route['admin/categories/(:any)'] = 'admin_categories/index/$1'; //$1 = page number

$route['admin/courses'] = 'admin_courses/index';
$route['admin/courses/add'] = 'admin_courses/add';
$route['admin/courses/update'] = 'admin_courses/update';
$route['admin/courses/update/(:any)'] = 'admin_courses/update/$1';
$route['admin/courses/delete/(:any)'] = 'admin_courses/delete/$1';
$route['admin/courses/(:any)'] = 'admin_courses/index/$1'; //$1 = page number

$route['admin/books/add/(:any)'] = 'admin_books/add/$1';
$route['admin/books/update/(:any)'] = 'admin_books/update/$1';
$route['admin/books/delete/(:any)'] = 'admin_books/delete/$1';
$route['admin/books/(:any)'] = 'admin_books/index/$1'; //$1 = page number

$route['admin/ibooks'] = 'admin_ibooks/index';
$route['admin/ibooks/add'] = 'admin_ibooks/add';
$route['admin/ibooks/update'] = 'admin_ibooks/update';
$route['admin/ibooks/update/(:any)'] = 'admin_ibooks/update/$1';
$route['admin/ibooks/delete/(:any)'] = 'admin_ibooks/delete/$1';
$route['admin/ibooks/(:any)'] = 'admin_ibooks/index/$1'; //$1 = page number

$route['admin/chapters'] = 'admin_chapters/index';
$route['admin/chapters/add/(:any)'] = 'admin_chapters/add/$1';
$route['admin/chapters/update/(:any)'] = 'admin_chapters/update/$1';
$route['admin/chapters/delete/(:any)'] = 'admin_chapters/delete/$1';
$route['admin/chapters/(:any)'] = 'admin_chapters/index/$1'; //$1 = page number

$route['admin/ichapters'] = 'admin_ichapters/index';
$route['admin/ichapters/add'] = 'admin_ichapters/add';
$route['admin/ichapters/update'] = 'admin_ichapters/update';
$route['admin/ichapters/update/(:any)'] = 'admin_ichapters/update/$1';
$route['admin/ichapters/delete/(:any)'] = 'admin_ichapters/delete/$1';
$route['admin/ichapters/(:any)'] = 'admin_ichapters/index/$1'; //$1 = page number

$route['admin/sentences/add/(:any)'] = 'admin_sentences/add/$1';
$route['admin/sentences/update/(:any)'] = 'admin_sentences/update/$1';
$route['admin/sentences/delete/(:any)'] = 'admin_sentences/delete/$1';
$route['admin/sentences/(:any)'] = 'admin_sentences/index/$1'; //$1 = page number

$route['admin/isentences'] = 'admin_isentences/index';
$route['admin/isentences/add'] = 'admin_isentences/add';
$route['admin/isentences/update'] = 'admin_isentences/update';
$route['admin/isentences/update/(:any)'] = 'admin_isentences/update/$1';
$route['admin/isentences/delete/(:any)'] = 'admin_isentences/delete/$1';
$route['admin/isentences/(:any)'] = 'admin_isentences/index/$1'; //$1 = page number

$route['admin/stores'] = 'admin_stores/index';
$route['admin/stores/add'] = 'admin_stores/add';
$route['admin/stores/update'] = 'admin_stores/update';
$route['admin/stores/update/(:any)'] = 'admin_stores/update/$1';
$route['admin/stores/delete/(:any)'] = 'admin_stores/delete/$1';
$route['admin/stores/(:any)'] = 'admin_stores/index/$1'; //$1 = page number

$route['admin/offers'] = 'admin_offers/index';
$route['admin/offers/add'] = 'admin_offers/add';
$route['admin/offers/add/(:any)'] = 'admin_offers/add/$1'; //$1 = store id
$route['admin/offers/update'] = 'admin_offers/update';
$route['admin/offers/update/(:any)'] = 'admin_offers/update/$1';
$route['admin/offers/complete/(:any)/(:any)/(:any)'] = 'admin_offers/complete/$1/$2/$3'; //$1 = offers id, $2 = buys id, $3 = quantity
$route['admin/offers/delete/(:any)/(:any)'] = 'admin_offers/delete/$1/$2';
$route['admin/offers/(:any)'] = 'admin_offers/index/$1'; //$1 = page number
$route['admin/offers/(:any)/(:any)'] = 'admin_offers/index/$1/$2'; //$1 = offer id, $2 = page number
$route['admin/offers/get_sub_categories'] = 'admin_offers/get_sub_categories';

$route['admin/report'] = 'admin_report/index';
$route['admin/report/(:any)'] = 'admin_report/index/$1'; //$1 = store id

$route['admin/residents'] = 'admin_residents/index';
$route['admin/residents/add'] = 'admin_residents/add';
$route['admin/residents/update'] = 'admin_residents/update';
$route['admin/residents/upload_image'] = 'admin_residents/upload_image';
$route['admin/residents/delete_image'] = 'admin_residents/delete_image';
$route['admin/residents/update/(:any)'] = 'admin_residents/update/$1';
$route['admin/residents/delete'] = 'admin_residents/delete';
$route['admin/residents/get_question_info'] = 'admin_residents/get_question_info';
//$route['admin/residents/delete/(:any)'] = 'admin_residents/delete/$1';
$route['admin/residents/answers/(:any)'] = 'admin_residents/answers/$1'; //$1 = residents id
$route['admin/residents/(:any)'] = 'admin_residents/index/$1'; //$1 = page number


$route['admin/videos'] = 'admin_videos/index';
$route['admin/videos/add'] = 'admin_videos/add';
$route['admin/videos/update'] = 'admin_videos/update';
$route['admin/videos/update/(:any)'] = 'admin_videos/update/$1';
$route['admin/videos/delete/(:any)'] = 'admin_videos/delete/$1';
$route['admin/videos/(:any)'] = 'admin_videos/index/$1'; //$1 = page number

$route['admin/mail'] = 'admin_mail/index';
$route['admin/mail/update'] = 'admin_mail/update';
$route['admin/mail/update/(:any)'] = 'admin_mail/update/$1';
$route['admin/mail/delete/(:any)'] = 'admin_mail/delete/$1';
$route['admin/mail/(:any)'] = 'admin_mail/index/$1'; //$1 = page number

$route['admin/point'] = 'admin_point/index';
$route['admin/point/update'] = 'admin_point/update';
$route['admin/point/update/(:any)'] = 'admin_point/update/$1';
$route['admin/point/delete/(:any)'] = 'admin_point/delete/$1';
$route['admin/point/(:any)'] = 'admin_point/index/$1'; //$1 = page number


/* End of file routes.php */
/* Location: ./application/config/routes.php */