<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', function() {
    return view('home_view');
});

$routes->get('/auth/login', function() {
    return view('login_view');
});

$routes->get('/auth/register', function() {
    return view('register_view');
});

$routes->post('login', 'Auth::login');
$routes->post('register', 'Auth::register');


$routes->get('products', 'ProductController::index');
$routes->post('products', 'ProductController::create');

$routes->get('upload', function() {
    return view('upload');
});