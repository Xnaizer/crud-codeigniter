<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->get('/products', 'ProductController::index');
$routes->get('/products/detail/(:num)', 'ProductController::detail/$1');

$routes->get('/auth/login', function() {
    return view('login_view');
});

$routes->get('/auth/register', function() {
    return view('register_view');
});

$routes->post('/login', 'Auth::login');
$routes->post('/register', 'Auth::register');

$routes->group('api', ['filter' => 'jwt'], function($routes){
    $routes->get('images', 'ImageController::index');
    $routes->post('images', 'ImageController::create');
    $routes->post('images/(:num)', 'ImageController::update/$1');
    $routes->delete('images/(:num)', 'ImageController::delete/$1');
    $routes->delete('images', 'ImageController::deleteAll');
});