<?php

// Đăng ký các route cho phần web
$routes->get('/', 'MoviesController::index');
$routes->get('product/(:num)/(:string)', 'ProductController::show:$1:$2');
$routes->post('product/create', 'ProductController::create');
$routes->put('product/update/(:num)', 'ProductController::update/$1');
$routes->delete('product/delete/(:num)', 'ProductController::delete/$1');

// $routes->get('admin/index/', 'AdminController::index');
// $routes->get('admin/(:any)/(:any)', 'AdminController::$1:$2');

// $routes->get('admin/(:any)', 'AdminController::$1', [
//     \App\Middleware\AuthMiddleware::class,
//     \App\Middleware\PermissionMiddleware::class
// ]); //router de khi goi bat ky admin/string nào nó cũng gọi đến Controller -> Action

// $routes->post('admin/edit', 'AdminController::edit', [
//     \App\Middleware\AuthMiddleware::class,
//     \App\Middleware\PermissionMiddleware::class
// ]);

// $routes->delete('admin/delete', 'AdminController::delete', [
//     \App\Middleware\AuthMiddleware::class,
//     \App\Middleware\PermissionMiddleware::class
// ]);
$routes->get('account/logout', 'Backend\AuthController::logout',[\App\Middleware\AuthMiddleware::class]);
$routes->get('account/profile', 'Backend\AuthController::profile',[\App\Middleware\AuthMiddleware::class]);
$routes->post('account/profile', 'Backend\AuthController::profile',[\App\Middleware\AuthMiddleware::class]);
$routes->get('account/(:any)/(:any)', 'Backend\AuthController::$1:$2',[\App\Middleware\NoauthMiddleware::class]);
$routes->post('account/(:any)/(:any)', 'Backend\AuthController::$1:$2',[\App\Middleware\NoauthMiddleware::class]);
$routes->get('account/(:any)/(:any)/(:any)', 'Backend\AuthController::$1:$2:$2',[\App\Middleware\NoauthMiddleware::class]);
$routes->post('account/(:any)/(:any)', 'Backend\AuthController::$1:$2:$2',[\App\Middleware\NoauthMiddleware::class]);
$routes->get('account/(:any)/', 'Backend\AuthController::$1',[\App\Middleware\NoauthMiddleware::class]);
$routes->post('account/(:any)', 'Backend\AuthController::$1',[\App\Middleware\NoauthMiddleware::class]);

$routes->get('admin/(:any)/(:any)', 'Backend\$1Controller::$2',[\App\Middleware\AuthMiddleware::class,\App\Middleware\RolesMiddleware::class]);
$routes->post('admin/(:any)/(:any)', 'Backend\$1Controller::$2',[\App\Middleware\AuthMiddleware::class,\App\Middleware\RolesMiddleware::class]);
$routes->get('admin/(:any)', 'Backend\$1Controller::index',[\App\Middleware\AuthMiddleware::class,\App\Middleware\RolesMiddleware::class]);
$routes->get('admin', 'Backend\AuthController::index',[\App\Middleware\AuthMiddleware::class,\App\Middleware\RolesMiddleware::class]);


// $routes->post('admin/terms/(:any)/(:any)/(:any)/(:num)', 'Backend\TermsController::$3:$1:$2:$4',[\App\Middleware\AuthMiddleware::class,\App\Middleware\RolesMiddleware::class]);
// $routes->get('admin/terms/(:any)/(:any)/(:any)/(:num)', 'Backend\TermsController::$3:$1:$2:$4',[\App\Middleware\AuthMiddleware::class,\App\Middleware\RolesMiddleware::class]); // $routes->get('admin/terms/(:any)/(:any)', 'Backend\TermsController::index:$1:$2',[\App\Middleware\AuthMiddleware::class,\App\Middleware\RolesMiddleware::class]); // $routes->post('admin/terms/(:any)/(:any)', 'Backend\TermsController::index:$1:$2',[\App\Middleware\AuthMiddleware::class,\App\Middleware\RolesMiddleware::class]);

$routes->get('admin/(:any)/(:any)/(:any)/(:any)/(:any)', 'Backend\$1Controller::$2:$3:$4:$5',[\App\Middleware\AuthMiddleware::class,\App\Middleware\RolesMiddleware::class]);
$routes->post('admin/(:any)/(:any)/(:any)/(:any)/(:any)', 'Backend\$1Controller::$2:$3:$4:$5',[\App\Middleware\AuthMiddleware::class,\App\Middleware\RolesMiddleware::class]);
$routes->get('admin/(:any)/(:any)', 'Backend\$1Controller::$2',[\App\Middleware\AuthMiddleware::class,\App\Middleware\RolesMiddleware::class]);
$routes->post('admin/(:any)/(:any)', 'Backend\$1Controller::$2',[\App\Middleware\AuthMiddleware::class,\App\Middleware\RolesMiddleware::class]);
$routes->get('admin/(:any)', 'Backend\$1Controller::index',[\App\Middleware\AuthMiddleware::class,\App\Middleware\RolesMiddleware::class]);
$routes->get('admin', 'Backend\AuthController::index',[\App\Middleware\AuthMiddleware::class,\App\Middleware\RolesMiddleware::class]);
