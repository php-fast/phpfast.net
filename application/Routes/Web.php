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


$routes->get('admin/(:any)/(:any)', 'Backend\$1Controller::$2');
$routes->post('admin/(:any)/(:any)', 'Backend\$1Controller::$2');
$routes->get('admin/(:any)', 'Backend\$1Controller::index');
$routes->get('admin', 'Backend\AuthController::index');