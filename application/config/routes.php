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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['default_controller'] = 'main';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Auth Routes
$route['login'] = 'auth/login';
$route['register'] = 'auth/register';
$route['forgot-password'] = 'auth/forgot_password';
$route['logout'] = 'auth/logout';

// Auth Process Routes
$route['auth/login'] = 'auth/process_login';
$route['auth/register'] = 'auth/process_register';
$route['auth/ajax_register'] = 'auth/ajax_register';

// Dashboard Route (we'll create this next)
$route['dashboard'] = 'dashboard';

// Clients Routes
$route['clients'] = 'clients';
$route['clients/add'] = 'clients/add';
$route['clients/edit/(:num)'] = 'clients/edit/$1';
$route['clients/view/(:num)'] = 'clients/view/$1';
$route['clients/delete/(:num)'] = 'clients/delete/$1';
$route['clients/process_add'] = 'clients/process_add';
$route['clients/process_edit/(:num)'] = 'clients/process_edit/$1';

// Vendors Routes
$route['vendors'] = 'vendors';
$route['vendors/add'] = 'vendors/add';
$route['vendors/edit/(:num)'] = 'vendors/edit/$1';
$route['vendors/view/(:num)'] = 'vendors/view/$1';
$route['vendors/delete/(:num)'] = 'vendors/delete/$1';
$route['vendors/process_add'] = 'vendors/process_add';
$route['vendors/process_edit/(:num)'] = 'vendors/process_edit/$1';

// Sales Routes
$route['sales'] = 'sales';
$route['sales/create'] = 'sales/create';
$route['sales/edit/(:num)'] = 'sales/edit/$1';
$route['sales/view/(:num)'] = 'sales/view/$1';
$route['sales/delete/(:num)'] = 'sales/delete/$1';
$route['sales/invoices'] = 'sales/invoices';
$route['sales/process_create'] = 'sales/process_create';
$route['sales/process_edit/(:num)'] = 'sales/process_edit/$1';
$route['sales/create_invoice/(:num)'] = 'sales/create_invoice/$1';
$route['sales/update_status/(:num)'] = 'sales/update_status/$1';
