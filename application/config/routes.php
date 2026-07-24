<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'auth/index';
$route['logout'] = 'auth/logout';
$route['dashboard'] = 'dashboard/index';
$route['api-docs'] = 'api_docs/index';

$route['pesanan'] = 'pesanan/index';
$route['pesanan/(:num)'] = 'pesanan/show/$1';
$route['pesanan/(:num)/status']['post'] = 'pesanan/update_status/$1';

$route['api/login']['post'] = 'api/auth_api/login';
$route['api/logout']['post'] = 'api/auth_api/logout';
$route['api/profile']['get'] = 'api/auth_api/profile';

$route['api/kategori']['get'] = 'api/kategori_api/index';
$route['api/kategori/(:num)']['get'] = 'api/kategori_api/show/$1';

$route['api/barang']['get'] = 'api/barang_api/index';
$route['api/barang/(:num)']['get'] = 'api/barang_api/show/$1';

$route['api/pesanan']['get'] = 'api/pesanan_api/index';
$route['api/pesanan']['post'] = 'api/pesanan_api/create';
$route['api/pesanan/(:num)']['get'] = 'api/pesanan_api/show/$1';
