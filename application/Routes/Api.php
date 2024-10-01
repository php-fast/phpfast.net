<?php

// Đăng ký routes cho API
$routes->get('api/users', 'Api\UsersController::index');
$routes->get('api/users/(:num)', 'Api\UsersController::show:$1');
$routes->get('api/users/(:any)', 'Api\UsersController::$1');
$routes->post('api/users', 'Api\UsersController::store');
$routes->put('api/users/(:num)', 'Api\UsersController::update:$1');
$routes->delete('api/users/(:num)', 'Api\UsersController::delete:$1');
